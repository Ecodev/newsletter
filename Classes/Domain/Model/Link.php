<?php

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
 * Link
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_Link extends Tx_Extbase_DomainObject_AbstractEntity
{

    /**
     * url
     *
     * @var string $url
     */
    protected $url;

    /**
     * newsletter
     * @lazy
     * @var integer $newsletter
     */
    protected $newsletter;

    /**
     * opened count
     *
     * @var integer $openedCount
     */
    protected $openedCount;

    /**
     * @var Tx_Extbase_Object_ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Override parent to manually inject objectManager
     */
    public function initializeObject()
    {
        $this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
    }

    /**
     * Setter for url
     *
     * @param string $url url
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Getter for url
     *
     * @return string url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Setter for newsletter
     *
     * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter newsletter
     * @return void
     */
    public function setNewsletter(Tx_Newsletter_Domain_Model_Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Getter for newsletter
     *
     * @return Tx_Newsletter_Domain_Model_Newsletter newsletter
     */
    public function getNewsletter()
    {
        $newsletterRepository = $this->objectManager->get('Tx_Newsletter_Domain_Repository_NewsletterRepository');
        return $newsletterRepository->findByUid($this->newsletter);
    }

    /**
     * Setter for openedCount
     *
     * @param integer $openedCount openedCount
     * @return void
     */
    public function setOpenedCount($openedCount)
    {
        $this->openedCount = $openedCount;
    }

    /**
     * Getter for openedCount
     *
     * @return integer openedCount
     */
    public function getOpenedCount()
    {
        return $this->openedCount;
    }

    public function getOpenedPercentage()
    {
        $emailRepository = $this->objectManager->get('Tx_Newsletter_Domain_Repository_EmailRepository');
        $emailCount = $emailRepository->getCount($this->newsletter);

        if ($emailCount == 0)
            return 0;

        return round($this->getOpenedCount() * 100 / $emailCount, 2);
    }

}
