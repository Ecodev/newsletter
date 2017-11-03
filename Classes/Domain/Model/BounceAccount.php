<?php

namespace Ecodev\Newsletter\Domain\Model;

use Ecodev\Newsletter\Tools;

/**
 * BounceAccount
 */
class BounceAccount extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * email
     *
     * @var string
     * @validate NotEmpty
     */
    protected $email = '';

    /**
     * server
     *
     * @var string
     */
    protected $server = '';

    /**
     * protocol
     *
     * @var string
     */
    protected $protocol = '';

    /**
     * port
     *
     * @var int
     */
    protected $port = 0;

    /**
     * username
     *
     * @var string
     */
    protected $username = '';

    /**
     * password
     *
     * @var string
     */
    protected $password = '';

    /**
     * fetchmail configuration
     *
     * @var string
     */
    protected $config = '';

    /**
     * Setter for email
     *
     * @param string $email email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Getter for email
     *
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Setter for server
     *
     * @param string $server server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * Getter for server
     *
     * @return string server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Setter for protocol
     *
     * @param string $protocol protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Getter for protocol
     *
     * @return string protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Setter for port
     *
     * @param int $port port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Getter for port
     *
     * @return int port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Setter for username
     *
     * @param string $username username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Getter for username
     *
     * @return string username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Setter for password
     *
     * @param string $password password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Getter for password
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setter for config
     *
     * @param string $config config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Getter for config
     *
     * @return string config
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function getSubstitutedConfig()
    {
        $markers = ['###SERVER###', '###PROTOCOL###', '###PORT###', '###USERNAME###', '###PASSWORD###'];
        $values = [];
        $values[] = $this->getServer();
        $values[] = $this->getProtocol();
        $values[] = $this->getPort();
        $values[] = $this->getUsername();
        $values[] = Tools::decrypt($this->getPassword());

        $config = $this->getConfig();
        if (empty($config)) {
            // Keep the old config to not break old installations
            $config = 'poll ###SERVER### proto ###PROTOCOL### username "###USERNAME###" password "###PASSWORD###"';
        } else {
            $config = Tools::decrypt($config);
        }

        $result = str_replace($markers, $values, $config);
        unset($values); // Dont leave unencrypted values in memory around for too long.

        return $result;
    }
}
