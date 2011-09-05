<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Fabien Udriot <fabien.udriot@ecodev.ch>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
 * Class to import data from legacy tx_tcdirectmail
 *
 * @author		Fabien Udriot <fabien.udriot@ecodev.ch>
 * @package		TYPO3
 * @subpackage	tx_newsletter
 *
 * $Id$
 */
class ext_update {

	/**
	 * SQL queries to copy data from tcdirectmail to newsletter
	 * @var array
	 */
	private $queries = array(

		// Recipient lists
		"INSERT INTO tx_newsletter_domain_model_recipientlist (
			uid, pid, title, plain_only, lang, type, be_users, fe_groups, fe_pages, csv_url, csv_separator, csv_fields, csv_filename, csv_values, sql_statement, html_url, html_fetch_type, calculated_recipients, tstamp, crdate, deleted, hidden
		) SELECT uid, pid, title, plain_only, lang, 
		CONCAT( 'Tx_Newsletter_Domain_Model_RecipientList_', CONCAT( UPPER( LEFT( REPLACE( targettype, 'tx_tcdirectmail_target_', '' ) , 1 ) ) , SUBSTRING( REPLACE( targettype, 'tx_tcdirectmail_target_', '' ) , 2 ) ) ),
		beusers, fegroups, fepages, csvurl, csvseparator, csvfields, csvfilename, csvvalues, rawsql, htmlfile, htmlfetchtype, calculated_receivers, tstamp, crdate, deleted, hidden
		FROM tx_tcdirectmail_targets;",
		
		// Emails
		"INSERT INTO tx_newsletter_domain_model_email (
			pid, begin_time, end_time, recipient_address, recipient_data, open_time, bounce_time, host
		) SELECT pid, begintime, sendtime, receiver, userdata, beenthere, bounced, host
		FROM tx_tcdirectmail_sentlog;",
	
		// Bounce accounts
		"INSERT INTO tx_newsletter_domain_model_bounceaccount (
			pid, email, server, protocol, username, password, tstamp, crdate, deleted, hidden
		) SELECT pid, email, server, servertype, username, passwd, tstamp, crdate, deleted, hidden
		FROM tx_tcdirectmail_bounceaccount;",
	
		// Migrate newsletter from page to its own table
		"INSERT INTO tx_newsletter_domain_model_newsletter (
			pid, planned_time, recipient_list, repetition, sender_name, sender_email, plain_converter, attachments, inject_open_spy, inject_links_spy, bounce_account
		) SELECT uid, tx_tcdirectmail_senttime, tx_tcdirectmail_real_target, tx_tcdirectmail_repeat, tx_tcdirectmail_sendername, tx_tcdirectmail_senderemail, 
		CONCAT( 'Tx_Newsletter_Domain_Model_PlainConverter_', CONCAT( UPPER( LEFT( REPLACE( tx_tcdirectmail_plainconvert, 'tx_tcdirectmail_plain_', '' ) , 1 ) ) , SUBSTRING( REPLACE( tx_tcdirectmail_plainconvert, 'tx_tcdirectmail_plain_', '' ) , 2 ) ) ),
		tx_tcdirectmail_attachfiles, tx_tcdirectmail_spy, tx_tcdirectmail_register_clicks, tx_tcdirectmail_bounceaccount
		FROM pages
		WHERE tx_tcdirectmail_real_target != 0;",
		
		// Migrate CLI beuser 
		"INSERT INTO be_users (
			pid, tstamp, username, password, admin, usergroup, disable, starttime, endtime, lang, email, db_mountpoints, options, crdate, cruser_id, realName, userMods, allowed_languages, uc, file_mountpoints, fileoper_perms, workspace_perms, lockToDomain, disableIPlock, deleted, TSconfig, lastlogin, createdByAction, usergroup_cached_list, workspace_id, workspace_preview
		) SELECT pid, tstamp, REPLACE(username, 'tcdirectmail', 'newsletter') AS username, password, admin, usergroup, disable, starttime, endtime, lang, email, db_mountpoints, options, crdate, cruser_id, realName, userMods, allowed_languages, uc, file_mountpoints, fileoper_perms, workspace_perms, lockToDomain, disableIPlock, deleted, TSconfig, lastlogin, createdByAction, usergroup_cached_list, workspace_id, workspace_preview
		FROM be_users
		WHERE username = '_cli_tcdirectmail';",
			
		"UPDATE fe_users SET tx_newsletter_bounce = tx_tcdirectmail_bounce WHERE tx_newsletter_bounce = 0;",
		"UPDATE be_users SET tx_newsletter_bounce = tx_tcdirectmail_bounce WHERE tx_newsletter_bounce = 0;",
	
		// Normalize case
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_BeUsers' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Beusers';",
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_FeGroups' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Fegroups';",
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_FePages' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Fepages';",
		"DELETE FROM tx_newsletter_domain_model_recipientlist WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Ttaddress';", 
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_Sql' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Sql';", 
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_CsvFile' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Csvfile';", 
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_CsvList' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Csvlist';", 
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_CsvUrl' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_Csvurl';", 
		"UPDATE tx_newsletter_domain_model_recipientlist SET type = 'Tx_Newsletter_Domain_Model_RecipientList_Html' WHERE type = 'Tx_Newsletter_Domain_Model_RecipientList_html';",
	
		// TODO link email to newsletter according to PID
		//"UPDATE tx_newsletter_domain_model_email SET newsletter = LEFT JOIN???;",
		// TODO define begin_time and end_time for newsletter based on tx_tcdirectmail_lock
	);

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return	string	HTML to display
	 */
	function main()
	{	
		$content = '';
		global $TYPO3_DB;
 
		// Action! Makes the necessary update
		$update = t3lib_div::_GP('importtcdirectmail');
		
		// The update button was clicked
		if (!empty($update) && $this->canImportFromTcdirectmail())
		{
			$content .= '<h2>Import successfull</h2>';
			
			// Attempt to deactivate tcdirectmail via a URL loaded within iframe
			if (t3lib_div::_GP('deactivate'))
			{
				$content .= '<p>Deactivated TCDirectmail.</p>';
				$content .= '<iframe style="border: none; height: 0; width: 0;" src="/typo3/mod.php?M=tools_em&CMD[showExt]=tcdirectmail&CMD[remove]=1" width="0" height="0"></iframe>';
			}
			
			// Import data
			$recordCount = $this->importFromTcdirectmail();
			$content .= '<p>Modified records count: ' . $recordCount . '</p>';
		}
		else 
		{
			$content .= '<h2>Import from TCDirectMail</h2>';
			
			if ($this->canImportFromTcdirectmail())
			{
				$content .= '<form name="importForm" action="" method ="post">';
				$content .= '<p>Import all data from TCDirectmail, including newsletter sent, to be send and statistics.</p>';
				$content .= '<input type="checkbox" name="deactivate" id="deactivate" checked="checked" /><label for="deactivate">Attempt to deactivate TCDirectmail.</label>';
				$content .= '<p><input type="submit" name="importtcdirectmail" value ="Import" /></p>';
				$content .= '</form>';
			}
			else
			{
				$content .= '<p>TCDirectmail not found, or Newsletter tables non-empty (already imported).</p>';
			}
		}		
		
		return $content;
	}
	
	/**
	 * Returns whether an import from tcdirectmail is possible
	 * @return boolean
	 */
	private function canImportFromTcdirectmail()
	{
		global $TYPO3_DB;
		
		// Check that tcdirectmail tables exist
		$requiredTables = array(
			'tx_tcdirectmail_bounceaccount',
			'tx_tcdirectmail_clicklinks',
			'tx_tcdirectmail_lock',
			'tx_tcdirectmail_sentlog',
			'tx_tcdirectmail_targets',
		);
		
		$tables = array_keys($TYPO3_DB->admin_get_tables());
		$missingTables = array_diff($requiredTables, $tables);
		
		if (count($missingTables) != 0)
			return false;

		// Check that newsletter tables are empty otherwise we would have primary key collision
		$emptyTables = array(
			'tx_newsletter_domain_model_bounceaccount',
			'tx_newsletter_domain_model_email',
			'tx_newsletter_domain_model_recipientlist',
			'tx_newsletter_domain_model_newsletter',
		);
	
		foreach ($emptyTables as $table)
		{
			$res = $TYPO3_DB->sql_query("SELECT COUNT(*) AS count FROM $table");
			$row = $TYPO3_DB->sql_fetch_row($res);
			if ($row[0] != 0)
				return false;
		}
		
		return true;
	}

	/**
	 * Import data from tcdirectmail. Assume everything is available for import.
	 */
	private function importFromTcdirectmail()
	{
		global $TYPO3_DB;
		
		$recordCount = 0;
		foreach ($this->queries as $query)
		{
			$res = $TYPO3_DB->sql_query($query);
			if ($error = $TYPO3_DB->sql_error()) die("<pre>" .  $query . "<br>" . $error . "</pre>");
			$recordCount += $TYPO3_DB->sql_affected_rows($res);
		}
	
		// Copy uploaded files from tcdirectmail directory to newsletter directory
		foreach (glob(PATH_site."uploads/tx_tcdirectmail/*") as $filename)
		{
			$dest = str_replace('uploads/tx_tcdirectmail/', 'uploads/tx_newsletter/', $filename);
			copy($filename, $dest);
		}
		
		return $recordCount;
	}

	/**
	 * This method checks whether it is necessary to display the UPDATE option at all
	 *
	 * @param	string	$what: What should be updated
	 */
	function access($what = 'all') {
		return TRUE;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newsletter/class.ext_update.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newsletter/class.ext_update.php']);
}

