<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Fabien Udriot (fabien.udriot@ecodev.ch)
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
***************************************************************/


/**
 * Class "tx_newsletter_NewsletterTask" provides Scheduler integration
 *
 * @author		Fabien Udriot <fabien.udriot@ecodev.ch>
 * @package		TYPO3
 * @subpackage	tx_newsletter
 *
 * $Id: $
 */
class tx_newsletter_NewsletterTask extends tx_scheduler_Task {

	/**
	 * Function executed from the Scheduler.
	 * Sends an email
	 *
	 * @return	void
	 */
	public function execute() {
		$success = TRUE;
		
		// Send email
		$command = '/usr/bin/php ' . dirname(PATH_thisScript) . 'conf/ext/newsletter/cli/mailer.php';
		$resultMailer = exec($command);
		
		if ($resultMailer != '') {
			$command = 'echo  "' . $resultMailer . '" >> /tmp/tcdiremail.log';
			exec($command);
		}
		
		return $success;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/scheduler/examples/class.tx_newsletter_newslettertask.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/scheduler/examples/class.tx_newsletter_newslettertask.php']);
}


