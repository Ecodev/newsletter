<?php

namespace Ecodev\Newsletter\Domain\Model;

use DateTime;
use Ecodev\Newsletter\Utility\UriBuilder;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
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
 * Email
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Email extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * beginTime
     *
     * @var DateTime
     */
    protected $beginTime;

    /**
     * endTime
     *
     * @var DateTime
     */
    protected $endTime;

    /**
     * recipientAddress
     *
     * @var string
     * @validate NotEmpty
     */
    protected $recipientAddress = '';

    /**
     * recipientData
     *
     * @var string
     */
    protected $recipientData = 'a:0:{}';

    /**
     * openeTime
     *
     * @var DateTime
     */
    protected $openTime;

    /**
     * bounceTime
     *
     * @var DateTime
     */
    protected $bounceTime;

    /**
     * newsletter
     * @lazy
     * @var \Ecodev\Newsletter\Domain\Model\Newsletter
     */
    protected $newsletter;

    /**
     * Whether the recipient of this email requested to unsubscribe.
     *
     * @var bool
     * @validate NotEmpty
     */
    protected $unsubscribed = false;

    /**
     * Setter for beginTime
     *
     * @param DateTime $beginTime beginTime
     */
    public function setBeginTime(DateTime $beginTime)
    {
        $this->beginTime = $beginTime;
    }

    /**
     * Getter for beginTime
     *
     * @return DateTime beginTime
     */
    public function getBeginTime()
    {
        return $this->beginTime;
    }

    /**
     * Setter for endTime
     *
     * @param DateTime $endTime endTime
     */
    public function setEndTime(DateTime $endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * Getter for endTime
     *
     * @return DateTime endTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Setter for recipientAddress
     *
     * @param string $recipientAddress recipientAddress
     */
    public function setRecipientAddress($recipientAddress)
    {
        $this->recipientAddress = $recipientAddress;
    }

    /**
     * Getter for recipientAddress
     *
     * @return string recipientAddress
     */
    public function getRecipientAddress()
    {
        return $this->recipientAddress;
    }

    /**
     * Setter for recipientData
     *
     * @param array $recipientData recipientData
     */
    public function setRecipientData(array $recipientData)
    {
        $this->recipientData = serialize($recipientData);
    }

    /**
     * Getter for recipientData
     *
     * @return array recipientData
     */
    public function getRecipientData()
    {
        return unserialize($this->recipientData);
    }

    /**
     * Getter for authCode
     *
     * @return string authCode
     */
    public function getAuthCode()
    {
        return md5($this->getUid() . $this->getRecipientAddress());
    }

    /**
     * Setter for openTime
     *
     * @param DateTime $openTime openTime
     */
    public function setOpenTime(DateTime $openTime)
    {
        $this->openTime = $openTime;
    }

    /**
     * Getter for openTime
     *
     * @return DateTime openTime
     */
    public function getOpenTime()
    {
        return $this->openTime;
    }

    /**
     * Returns the state of opened
     *
     * @return bool the state of opened
     */
    public function isOpened()
    {
        return $this->getOpenTime() > 0;
    }

    /**
     * Setter for bounceTime
     *
     * @param DateTime $bounceTime bounceTime
     */
    public function setBounceTime(DateTime $bounceTime)
    {
        $this->bounceTime = $bounceTime;
    }

    /**
     * Getter for bounceTime
     *
     * @return DateTime bounceTime
     */
    public function getBounceTime()
    {
        return $this->bounceTime;
    }

    /**
     * Returns the state of bounced
     *
     * @return bool the state of bounced
     */
    public function isBounced()
    {
        return $this->getBounceTime() > 0;
    }

    /**
     * Setter for newsletter
     *
     * @param \Ecodev\Newsletter\Domain\Model\Newsletter $newsletter newsletter
     */
    public function setNewsletter(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Getter for newsletter
     *
     * @return \Ecodev\Newsletter\Domain\Model\Newsletter newsletter
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Setter for unsubscribed
     *
     * @param bool $unsubscribed Whether the recipient of this email requested to unsubscribe.
     */
    public function setUnsubscribed($unsubscribed)
    {
        $this->unsubscribed = $unsubscribed;
    }

    /**
     * Getter for unsubscribed
     *
     * @return bool Whether the recipient of this email requested to unsubscribe.
     */
    public function getUnsubscribed()
    {
        return $this->unsubscribed;
    }

    /**
     * Return the URL to view the newsletter
     * @return string
     */
    public function getViewUrl()
    {
        return UriBuilder::buildFrontendUri($this->getPid(), 'Email', 'show', ['c' => $this->getAuthCode()]);
    }

    /**
     * Return the URL to unsubscribe from the newsletter
     * @return string
     */
    public function getUnsubscribeUrl()
    {
        return UriBuilder::buildFrontendUri($this->getPid(), 'Email', 'unsubscribe', ['c' => $this->getAuthCode()]);
    }
}
