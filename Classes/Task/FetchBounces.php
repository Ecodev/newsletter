<?php

namespace Ecodev\Newsletter\Task;

use Ecodev\Newsletter\BounceHandler;

/**
 * Provides Scheduler task to fetch bounced emails
 */
class FetchBounces extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * Fetch bounce emails from servers, who will then be piped to cli/bounce.php for analysis
     *
     * @return bool	Returns true on successful execution, false on error
     */
    public function execute()
    {
        BounceHandler::fetchBouncedEmails();

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
        $bounceAccountRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\BounceAccountRepository::class);
        $bounceAccountCount = count($bounceAccountRepository->findAll());

        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('task_fetch_bounce_additional_information', 'newsletter', [$bounceAccountCount]);
    }
}
