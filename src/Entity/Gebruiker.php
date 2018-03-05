<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="GemeenteAmsterdam\FixxxSchuldhulp\Repository\GebruikerRepository")
 * @ORM\Table(
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uq_username", columns={"username"})
 *  }
 * )
 */
class Gebruiker implements UserInterface, \Serializable
{
    const TYPE_GKA = 'gka';
    const TYPE_MADI = 'madi';
    const TYPE_ADMIN = 'admin';

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     * Not mapped to database
     * @Assert\Length(min=8)
     */
    private $clearPassword;

    /**
     * @var \DateTime|NULL
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordChangedDateTime;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, nullable=false)
     * @Assert\NotBlank
     * @Assert\Choice(callback="getTypes")
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=255)
     */
    private $naam;

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
     */
    public function getRoles()
    {
        return ['ROLE_USER', 'ROLE_' . strtoupper($this->getType())];
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
        //
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getUsername()
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getPassword()
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getClearPassword()
    {
        return $this->clearPassword;
    }

    /**
     * @return \DateTime|NULL
     */
    public function getPasswordChangedDateTime()
    {
        return $this->passwordChangedDateTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;
    }

    public function setClearPassword($clearPassword)
    {
        $this->clearPassword = $clearPassword;
    }

    /**
     * @param \DateTime $passwordChangedDateTime
     */
    public function setPasswordChangedDateTime(\DateTime $passwordChangedDateTime = null)
    {
        $this->passwordChangedDateTime = $passwordChangedDateTime;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->naam . ' (' . $this->username . ')';
    }

    /**
     * {@inheritDoc}
     * @see Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password
        ]);
    }

    /**
     * {@inheritDoc}
     * @see Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->password = $data['password'];
    }

    /**
     * @return string[]
     */
    public static function getTypes()
    {
        return [
            self::TYPE_MADI => self::TYPE_MADI,
            self::TYPE_GKA => self::TYPE_GKA,
            self::TYPE_ADMIN => self::TYPE_ADMIN,
        ];
    }

    /**
     * @Assert\Callback
     */
    public function isValid(ExecutionContextInterface $context)
    {
        if ($this->getPassword() === null || $this->getPassword() === '') {
            if ($this->getClearPassword() === null || $this->getClearPassword() === '') {
                $context
                    ->buildViolation('A password is required for new users')
                    ->atPath('clearPassword')
                    ->addViolation();
            }
        }
    }
}