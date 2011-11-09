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
 * Repository for Tx_Newsletter_Domain_Model_Email
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
 
class Tx_Newsletter_Domain_Repository_EmailRepository extends Tx_Newsletter_Domain_Repository_AbstractRepository {

	/**
	 * Returns the latest newsletter for the given page
	 * @param string $authcode
	 * @return Tx_Newsletter_Domain_Model_Email
	 */
	public function findByAuthcode($authcode)
	{
		$query = $this->createQuery();
		$query->statement('SELECT * FROM `tx_newsletter_domain_model_email` WHERE MD5(CONCAT(`uid`, `recipient_address`)) = ? LIMIT 1', array($authcode));
		
		return $query->execute()->getFirst();
	}
	
	/**
	 * Returns the count of emails for a given newsletter
	 * @param integer $uidNewsletter
	 */
	public function getCount($uidNewsletter)
	{
		global $TYPO3_DB;
		$count = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'newsletter = ' . $uidNewsletter);

		return (int)$count;
	}
	
	public function findAllByNewsletter($uidNewsletter)
	{
		if ($uidNewsletter < 1)
			return $this->findAll();
		
		$query = $this->createQuery();
		$query->matching($query->equals('newsletter', $uidNewsletter));
		
		return $query->execute();
	}
	
	/**
	 * Returns statistics to be used for timeline chart
	 * @param integer $uidNewsletter 
	 * @return array eg: array(array(time, not_sent, sent, opened, bounced))
	 */
	public function getStatistics($uidNewsletter) {
		global $TYPO3_DB;
		$uidNewsletter = (int)$uidNewsletter;
	
		// SQL query which will retrieve statistics for all emails and links everytime an event happened to one email (sent, opened, or bounced) or one link (opened)
		// So in one (big) query, we get each step of the complete history of the newsletter
		$query= "
SELECT
	FROM_UNIXTIME(time.time) AS time,
	COUNT(DISTINCT IF(email.end_time NOT BETWEEN 1 AND time.time, email.uid, NULL)) AS not_sent,
	COUNT(DISTINCT IF(email.end_time BETWEEN 1 AND time.time AND email.open_time NOT BETWEEN 1 AND time.time AND email.bounce_time NOT BETWEEN 1 AND time.time , email.uid, NULL)) AS sent,
	COUNT(DISTINCT IF(email.open_time BETWEEN 1 AND time.time AND email.bounce_time NOT BETWEEN 1 AND time.time, email.uid, NULL)) AS opened,
	COUNT(DISTINCT IF(email.bounce_time BETWEEN 1 AND time.time, email.uid, NULL)) AS bounced,
	COUNT(DISTINCT IF(linkopened.open_time BETWEEN 1 AND time.time, linkopened.uid, NULL)) AS linkopened,
	COUNT(DISTINCT email.uid) AS total,
	COUNT(DISTINCT link.uid) AS linktotal
FROM (
	(SELECT end_time AS time FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND end_time)
	UNION
	(SELECT open_time AS time FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND open_time)
	UNION
	(SELECT bounce_time AS time FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND bounce_time)
	UNION
	(SELECT time.open_time AS time FROM `tx_newsletter_domain_model_linkopened` AS time INNER JOIN `tx_newsletter_domain_model_email` AS email ON (time.email = email.uid AND email.newsletter = $uidNewsletter) WHERE time.open_time)
) AS time
JOIN `tx_newsletter_domain_model_email` AS email ON (email.newsletter = $uidNewsletter)
JOIN `tx_newsletter_domain_model_email` AS email_linkopened ON (email_linkopened.newsletter = $uidNewsletter)
LEFT JOIN `tx_newsletter_domain_model_linkopened` AS linkopened ON (linkopened.email = email_linkopened.uid)
JOIN `tx_newsletter_domain_model_link` AS link ON (link.newsletter = $uidNewsletter)
GROUP BY time.time
ORDER BY time.time ASC";
		
		$result = array();
		$rs = $TYPO3_DB->sql_query($query);
		while ($row = $TYPO3_DB->sql_fetch_assoc($rs))
		{	
			// Compute percentage
			foreach (array('not_sent', 'sent', 'opened', 'bounced') as $status)
			{
				$row[$status . '_percentage'] = $row[$status] / $row['total'] * 100;
			}
			$row['linkopened_percentage'] = $row['linkopened'] / ($row['linktotal'] * $row['total']) * 100;
			
			$result[] = $row;
		}
		
		return $result;
	}
}
