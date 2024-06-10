<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\Azure;

enum AzureTokenRequestScope: string
{
    case STORAGE = 'https://storage.azure.com//.default';
    case MANAGEMENT = 'https://management.azure.com//.default';
}

use GemeenteAmsterdam\FixxxSchuldhulp\Azure\Config\SASFileReaderConfig;
use GemeenteAmsterdam\FixxxSchuldhulp\Azure\Config\SASFileWriterConfig;
use GuzzleHttp\RequestOptions;
use Http\Discovery\Exception\NotFoundException;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface as ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AzureStorage implements AzureStorageInterface
{
    private const CACHE_KEY = 'azure-access-token';
    private array $config;
    private string $accessToken;

    /**
     * @param HttpClientInterface $client
     * @param SASFileReaderConfig $SASFileReaderConfig
     * @param SASFileWriterConfig $SASFileWriterConfig
     * @param LoggerInterface $logger
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly SASFileReaderConfig $SASFileReaderConfig,
        private readonly SASFileWriterConfig $SASFileWriterConfig,
        private readonly LoggerInterface     $logger
    )
    {
        // Set an initial config, so getting an access token works
        $this->config = $this->SASFileReaderConfig->getConfig();
        $this->getAccessToken();
    }

    /**
     * Returns a url that is is signed with a SAS for reading
     * Microsoft API doc:
     *
     * @param string $filename
     * Filename of file you are reading
     *
     * @param string|null $destinationPath
     * Optional: path where the blob is stored
     *
     * @return string
     * returns an URL with SAS signature
     *
     *
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function generateURLForFileReading(string $filename, ?string $destinationPath): string
    {
        // Use a config to keep everything extensible
        $this->config = $this->SASFileReaderConfig->getConfig();

        // Create the signed blob URL
        return $this->getFileUrl($filename, $destinationPath);
    }

    /**
     * Store a file in the storage container and return the SAS signed URL for it
     * Microsoft API doc: https://learn.microsoft.com/en-us/rest/api/storageservices/put-blob
     *
     * @param UploadedFile $file
     * The file that should be stored
     *
     * @param string|null $destinationPath
     * Optional: the path where the file should be stored
     *
     * @param string|null $overrideFilename
     * Optional: Override the filename of the upload file for a cusyom filename
     *
     * @return string
     * returns a SAS signed url of the file
     *
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function storeFile(UploadedFile $file, ?string $destinationPath = null, ?string $overrideFilename = null): string
    {
        $this->config = $this->SASFileWriterConfig->getConfig();

        $uploadUrl = $this->getFileUrl($overrideFilename ?? $file->getClientOriginalName(), $destinationPath);

        $content = $file->getContent();

        $this->client->request(
            'PUT',
            $uploadUrl,
            [
                RequestOptions::BODY => $content,
                RequestOptions::HEADERS => [
                    'x-ms-blob-type' => 'BlockBlob',
                ],
            ]
        );

        return $this->generateURLForFileReading($overrideFilename ?? $file->getClientOriginalName(), $destinationPath);
    }

    /**
     * returns an array with file names
     * Microsoft API doc: https://learn.microsoft.com/en-us/rest/api/storageservices/list-blobs
     *
     * @param string|null $path
     *  The prefix of the whole filepath, can be used to search inside a directory
     *
     * @return array
     * The array with filenames, including prefixes
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function listFiles(?string $path = ''): array
    {
        $accessToken = $this->getAccessTokenFromAzure(AzureTokenRequestScope::STORAGE);

        $blobApiUrl = $this->createListBlobApiUrl($path);

        $apiResponse = $this->client->request(
            'GET',
            $blobApiUrl,
            [
                RequestOptions::HEADERS => [
                    'Authorization' => ' Bearer ' . $accessToken,
                    'x-ms-version' => '2017-11-09',
                    'Content-type' => 'application/json'
                ]
            ]
        );
        return $this->formatFileListResponse($apiResponse->getContent());
    }

    /**
     * Removes a file, if soft delete is enabled the file will be softdeleted
     * Microsoft API doc: https://learn.microsoft.com/en-us/rest/api/storageservices/delete-blob
     *
     * @param string|null $file
     *
     * @return bool
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function deleteFiles(?string $file): bool
    {
        $accessToken = $this->getAccessTokenFromAzure(AzureTokenRequestScope::STORAGE);

        $blobUrl = 'https://' .
            $this->config['storageAccount'] .
            '.blob.core.windows.net/' .
            $this->config['container'] .
            '/' .
            $file;

        $apiResponse = $this->client->request(
            'DELETE',
            $blobUrl,
            [
                RequestOptions::HEADERS => [
                    'Authorization' => ' Bearer ' . $accessToken,
                    'x-ms-version' => '2017-11-09',
                    'Content-type' => 'application/json'
                ]
            ]
        );

        if ($apiResponse->getStatusCode() != 202) {
            return false;
        }

        return true;
    }

    /**
     * Get the complete blob or throw a not found error
     * Microsoft API doc: https://learn.microsoft.com/en-us/rest/api/storageservices/get-blob
     *
     * @param string $file
     * The full path including filename and extension to the file
     *
     * @return ResponseInterface
     * The response from the API
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBlob(string $file): ResponseInterface
    {
        $accessToken = $this->getAccessTokenFromAzure(AzureTokenRequestScope::STORAGE);

        $blobUrl = 'https://' .
            $this->config['storageAccount'] .
            '.blob.core.windows.net/' .
            $this->config['container'] .
            '/' .
            $file;

        $apiResponse = $this->client->request(
            'GET',
            $blobUrl,
            [
                RequestOptions::HEADERS => [
                    'Authorization' => ' Bearer ' . $accessToken,
                    'x-ms-version' => '2017-11-09',
                ]
            ]
        );

        if ($apiResponse->getStatusCode() != 200) {
            throw new NotFoundException();
        }

        return $apiResponse;
    }

    /**
     * Get the content of a blob or throw a not found error
     *
     *
     * @param string $file
     * The full path including filename and extension to the file
     *
     * @return string
     * the file content
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBlobContent(string $file): string
    {
        $blob = $this->getBlob($file);

        return $blob->getContent();
    }

    /**
     * Sets the access token
     *
     * @param bool|null $invalidateCache
     * Optional: Boolean to tell the system to invalidate the cache
     *
     * @return void
     *
     *
     * @throws InvalidArgumentException
     * Can be thrown deep inside the Cache interface, see Symfony\Component\Cache\Traits\ConstraintsTrait:63
     */
    private function getAccessToken(?bool $invalidateCache = false): void
    {
        $cache = new FilesystemAdapter();
        if ($invalidateCache) {
            $cache->delete(self::CACHE_KEY);
        }

        $this->accessToken = $cache->get(self::CACHE_KEY, function (ItemInterface $item) {
            return $this->getAccessTokenFromAzure();
        });
    }

    /**
     * Get a new access token from Azure using the federated credentials
     *
     * @param string|null $scope
     * Ability to specify a scope for the accesstoken
     *
     * THE DOUBLE SLASH BELOW IS INTENDED!
     * Default: self::SCOPE_MANAGEMENT -> https://management.azure.com//.default
     * Scope for storage access (no SAS generation): self::SCOPE_STORAGE -> https://storage.azure.com//.default
     *
     * @return string
     * The new access token
     *
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getAccessTokenFromAzure(?AzureTokenRequestScope $scope = AzureTokenRequestScope::MANAGEMENT): string
    {
        $tokenUrl = $this->config['authorityHost'] . $this->config['tenantId'] . '/oauth2/v2.0/token';
        $grantType = 'client_credentials';
        $clientAssertion = file_get_contents($this->config['federatedTokenFile']);
        $clientAssertionType = 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer';

        // Prepare the request payload
        $payload = [
            'grant_type' => $grantType,
            'scope' => $scope->value,
            'client_assertion' => $clientAssertion,
            'client_assertion_type' => $clientAssertionType,
            'client_id' => $this->config['clientId'],
        ];

        $response = $this->client->request(
            'POST',
            $tokenUrl,
            [
                RequestOptions::HEADERS => ['Content-Type' => 'application/x-www-form-urlencoded'],
                RequestOptions::BODY => http_build_query($payload),
            ]
        );

        if ($response->getStatusCode() >= 400) {
            throw new \Exception(json_encode(['url' => $tokenUrl, 'payload' => $payload, 'config' => $this->config, 'response' => $response->getContent(false)]));
        }

        $body = $response->getContent(false);
        $data = json_decode($body, true);

        return $data['access_token'];
    }

    /**
     * Generate a SAS signature for a given file
     *
     *
     * @param string|null $filename
     * The filename of the blob
     *
     * @param string|null $destinationPath
     * Optional: the path where the file is be stored
     *
     * @return string
     * The SAS signature for the file with the active config
     *
     *
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function generateSASSignature(?string $filename = null, ?string $destinationPath = null): string
    {
        $canonicalizedResource = $this->getCanonicalizedResourcePath($filename, $destinationPath);

        $url = 'https://management.azure.com/subscriptions/'
            . $this->config['subscriptionId'] . '/resourceGroups/' . $this->config['resourceGroup']
            . '/providers/Microsoft.Storage/storageAccounts/' . $this->config['storageAccount']
            . '/listServiceSas?api-version=' . $this->config['apiVersion'];

        try {
            $response = $this->makeSasRequest($url, $canonicalizedResource);

            if ($response->getStatusCode() >= 400) {
                throw new \Exception();
            }
        } catch (\Exception) {
            $this->getAccessToken(true);
            $response = $this->makeSasRequest($url, $canonicalizedResource);
        }

        $body = $response->getContent(false);
        $data = json_decode($body, true);

        if (!array_key_exists('serviceSasToken', $data)) {
            throw new \Exception('Failed generating SAS signature');
        }

        return $data['serviceSasToken'];
    }

    /**
     * Make the POST request to Azure for getting a SAS token
     *
     *
     * @param string $url
     * @param string $canonicalizedResource
     *
     * @return ResponseInterface
     *
     *
     * @throws TransportExceptionInterface
     */
    private function makeSasRequest(string $url, string $canonicalizedResource): ResponseInterface
    {
        return $this->client->request(
            'POST',
            $url,
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                RequestOptions::JSON => [
                    'canonicalizedResource' => $canonicalizedResource,
                    'signedResource' => $this->config['signedResource'],
                    'signedPermission' => $this->config['permissions'],
                    'signedProtocol' => 'https',
                    'signedExpiry' => $this->config['expiry'],
                    'signedStart' => $this->config['start'],
                ],
            ]
        );
    }

    /**
     * Get the URL for accessing a file
     *
     * @param string $filename
     * The file name
     *
     * @param string|null $destinationPath
     * Optional: the path where the file is be stored
     *
     *
     * @return string
     * The URL to access the file using the SAS signature
     *
     *
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getFileUrl(string $filename, ?string $destinationPath = null): string
    {
        $signature = $this->generateSASSignature($filename, $destinationPath);

        $url = 'https://'
            . $this->config['storageAccount'] . '.blob.core.windows.net/'
            . $this->config['container'] . '/';

        if ($destinationPath) {
            $url .= trim($destinationPath, '/\\') . '/';
        }

        $url .= $filename . '?'
            . $signature;

        return $url;
    }

    /**
     * Generate the canonicalized resource path needed to generate the SAS signature
     *
     * @param string|null $filename
     * Optional: The file name
     * Caution: Make sure that if you do not pass any filename, your scope is a container
     *
     * @param string|null $destinationPath
     * Optional: the path where the file is be stored
     *
     * @return string
     * The canonicalized resource path
     */
    private function getCanonicalizedResourcePath(?string $filename, ?string $destinationPath = null): string
    {
        $canonicalizedResourcePath = '/blob/' . $this->config['storageAccount'] . '/' . $this->config['container'] . '/';

        if ($destinationPath) {
            $canonicalizedResourcePath .= trim($destinationPath, '/\\') . '/';
        }

        if ($filename) {
            $canonicalizedResourcePath .= $filename;
        }

        return $canonicalizedResourcePath;
    }

    /**
     * Creates an URL for listing files inside the container
     *
     * @param string|null $path
     * The prefix of the whole filepath, can be used to search inside a directory
     *
     * @return string
     * The URL which should list the files with the requested prefix
     */
    private function createListBlobApiUrl(?string $path = ''): string
    {
        $blobApiUrl = 'https://' .
            $this->config['storageAccount'] .
            '.blob.core.windows.net/' .
            $this->config['container'] .
            '?restype=container&comp=list&delimeter=%2F';

        if ($path) {
            $blobApiUrl .= '&prefix=' . urlencode($path);
        }

        return $blobApiUrl;
    }

    /**
     * Converts the Azure API response to an array with filenames. Each line contains the full name including path, filename and extension as a string
     *
     * @param string $xmlResponseString
     * The XML string response given by the Storage Blob API
     *
     * @return array
     * All files in the given response
     */
    private function formatFileListResponse(string $xmlResponseString): array
    {
        $files = [];
        $xml = simplexml_load_string($xmlResponseString);
        foreach ($xml->Blobs->Blob as $blob) {
            $files[] = (string)$blob->Name;
        }

        return $files;
    }
}
