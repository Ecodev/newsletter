<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011
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
***************************************************************/


/**
 * Repository for Tx_Newsletter_Domain_Model_Link
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Domain_Repository_LinkRepository extends Tx_Newsletter_Domain_Repository_AbstractRepository {

	/**
	 * Returns all links for a given newsletter
	 * @param integer $uidNewsletter
	 * @param integer $start
	 * @param integer $limit
	 * @return Tx_Newsletter_Domain_Model_Link[]
	 */
	public function findAllByNewsletter($uidNewsletter, $start, $limit)
	{
		if ($uidNewsletter < 1)
			return $this->findAll();

		$query = $this->createQuery();
		$query->matching($query->equals('newsletter', $uidNewsletter));
		$query->setLimit($limit);
		$query->setOffset($start);

		return $query->execute();
	}

	/**
	 * Returns the count of links for a given newsletter
	 * @global t3lib_DB $TYPO3_DB
	 * @param integer $uidNewsletter
	 */
	public function getCount($uidNewsletter)
	{
		global $TYPO3_DB;
		$count = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_link', 'newsletter = ' . $uidNewsletter);

		return (int)$count;
	}
	
	/**
	 * Register a clicked link in database and forward the event to RecipientList
	 * so it can optionnally do something more
	 * @global t3lib_DB $TYPO3_DB
	 * @param string $authCode identifier to find back the link
	 * @param boolean $isPlain
	 */
	public function registerClick($authCode, $isPlain)
	{
		global $TYPO3_DB;
		
		// Minimal sanitization before SQL
		$authCode = addslashes($authCode);
		$isPlain = $isPlain ? '1' : '0';
		
		// Insert an email-link record to register which user clicked on which link
		$TYPO3_DB->sql_query("
		INSERT INTO tx_newsletter_domain_model_linkopened (link, email, is_plain, open_time)
		SELECT tx_newsletter_domain_model_link.uid AS link, tx_newsletter_domain_model_email.uid AS email, $isPlain AS is_plain, " . time() . " AS open_time
		FROM tx_newsletter_domain_model_email
		LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
		LEFT JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
		WHERE
		MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = '$authCode'
		");

		// Increment the total count of clicks for the link opened (so if the emails record are deleted, we still know how many times the link was opened)
		$TYPO3_DB->sql_query("
		UPDATE tx_newsletter_domain_model_email
		LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
		LEFT JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
		SET tx_newsletter_domain_model_link.opened_count = tx_newsletter_domain_model_link.opened_count + 1 
		WHERE
		MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = '$authCode'
		");


		// Forward which user clicked the link to the recipientList so the recipientList may take appropriate action
		$rs = $TYPO3_DB->sql_query("
		SELECT tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.recipient_address
		FROM tx_newsletter_domain_model_email
		LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
		LEFT JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
		WHERE
		MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = '$authCode'
		AND recipient_list IS NOT NULL
		");
		
		if (list($recipientListUid, $email) = $TYPO3_DB->sql_fetch_row($rs)) {
			$recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
			$recipientList = $recipientListRepository->findByUid($recipientListUid);
			if ($recipientList)
			{
				$recipientList->registerClick($email);
			}
		}
	}
}
