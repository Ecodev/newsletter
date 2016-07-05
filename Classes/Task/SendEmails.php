<?php

namespace Ecodev\Newsletter\Task;

use Ecodev\Newsletter\Tools;

/**
 * Provides Scheduler task to send emails
 */
class SendEmails extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * Sends emails for queued newsletter
     *
     * @return bool	Returns true on successful execution, false on error
     */
    public function execute()
    {
        Tools::createAllSpool();
        Tools::runAllSpool();

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
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $newsletterRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\NewsletterRepository::class);

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

        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('task_send_emails_additional_information', 'newsletter', [$emailsPerRound, $emailNotSentCount, $newslettersToSendCount, $newslettersBeingSentCount]);
    }
}
