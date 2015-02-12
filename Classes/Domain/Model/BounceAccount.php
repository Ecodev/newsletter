<?php


namespace Ecodev\Newsletter\Domain\Model;

use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;



/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * BounceAccount
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BounceAccount extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * email
     *
     * @var string $email
     * @validate NotEmpty
     */
    protected $email;

    /**
     * server
     *
     * @var string $server
     */
    protected $server;

    /**
     * protocol
     *
     * @var string $protocol
     */
    protected $protocol;

    /**
     * username
     *
     * @var string $username
     */
    protected $username;

    /**
     * password
     *
     * @var string $password
     */
    protected $password;

    /**
     * Setter for email
     *
     * @param string $email email
     * @return void
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
     * @return void
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
     * @return void
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
     * Setter for username
     *
     * @param string $username username
     * @return void
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
     * @return void
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

}
