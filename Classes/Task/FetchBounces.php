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
}
