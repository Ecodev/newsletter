<?php


namespace Ecodev\Newsletter\Task;

use Ecodev\Newsletter\BounceHandler;
use tx_scheduler_Task;

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
 * Provides Scheduler task to fetch bounced emails
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FetchBounces extends tx_scheduler_Task
{

    /**
     * Fetch bounce emails from servers, who will then be piped to cli/bounce.php for analysis
     *
     * @return boolean	Returns true on successful execution, false on error
     */
    public function execute()
    {
        BounceHandler::fetchBouncedEmails();

        return true;
    }
}
