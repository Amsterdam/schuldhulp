<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\Doctrine;

use GemeenteAmsterdam\FixxxSchuldhulp\Azure\AzureDatabase;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Psr\Log\LoggerInterface;

class DynamicConnection extends Connection
{
    public function __construct(
        array                            $params,
        Driver                           $driver,
        ?Configuration                   $config = null,
        ?EventManager                    $eventManager = null,
        private readonly ?AzureDatabase  $azureDatabase = null,
        private readonly ?LoggerInterface $logger= null,
    )
    {
        if ($azureDatabase && $this->logger && isset($params['password'])) {
            $newPassword = $azureDatabase->getPassword($params['password']);
            $params = $this->addNewPasswordToParams($params, $newPassword);
        }

        parent::__construct($params, $driver, $config, $eventManager);

        if ($azureDatabase && $this->logger && isset($params['password'])) {
            try {
                $this->logger->debug(__CLASS__ . ':' . __LINE__ . ': Trying to connect to Azure DB');
                $this->connect();
            } catch (\Exception $e) {
                $this->logger->debug(__CLASS__ . ':' . __LINE__ . ": DB Connection failed. Trying to invalidate cache and set password again.");
                $newPassword = $azureDatabase->getPassword($params['password'], true);
                $this->logger->debug(__CLASS__ . ':' . __LINE__ . ": Got new password.");
                $params = $this->addNewPasswordToParams($params, $newPassword);
                parent::__construct($params, $driver, $config, $eventManager);
                $this->logger->debug(__CLASS__ . ':' . __LINE__ . ": Parent construct done.");
                $this->connect();
                $this->logger->debug(__CLASS__ . ':' . __LINE__ . ": New connect done.");
            }
            $this->logger->debug(__CLASS__ . ':' . __LINE__ . ': finished __construct if it is an azure DB');
        }
    }

    private function addNewPasswordToParams(array $params, string $newPassword): array
    {
        $params['password'] = $newPassword;
        $params['url'] = str_replace('temporary', $newPassword, $params['url']);

        return $params;
    }

}