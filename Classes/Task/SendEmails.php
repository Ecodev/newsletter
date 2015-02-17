<?php


namespace Ecodev\Newsletter\Task;

use Ecodev\Newsletter\Tools;
use GeneralUtility;
use tx_scheduler_Task;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Provides Scheduler task to send emails
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SendEmails extends tx_scheduler_Task
{

    /**
     * Sends emails for queued newsletter
     *
     * @return boolean	Returns true on successful execution, false on error
     */
    public function execute()
    {
        Tools::createAllSpool();
        Tools::runSpoolOneAll();

        return true;
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return	string	Information to display
     */
    public function getAdditionalInformation()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
        $newsletterRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\NewsletterRepository');

        $newslettersToSend = $newsletterRepository->findAllReadyToSend();
        $newslettersBeingSent = $newsletterRepository->findAllBeingSent();
        $newslettersToSendCount = count($newslettersToSend);
        $newslettersBeingSentCount = count($newslettersBeingSent);

        $emailNotSentCount = 0;
        foreach ($newslettersToSend as $newsletter) {
            $emailNotSentCount += $newsletter->getEmailNotSentCount();
        }
        foreach ($newslettersBeingSent as $newsletter) {
            $emailNotSentCount += $newsletter->getEmailNotSentCount();
        }

        $emailsPerRound = Tools::confParam('mails_per_round');

        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('task_send_emails_additional_information', 'newsletter', array($emailsPerRound, $emailNotSentCount, $newslettersToSendCount, $newslettersBeingSentCount));
    }
}
