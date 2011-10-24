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
	 * @param integer $pid
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
	
		// Build an SQL query which will retrieve statistics for all emails everytime an event happened to one email (sent, opened, or bounced)
		$union = array();
		foreach (array('end_time', 'open_time', 'bounce_time') as $fieldEvent) {
			$union []= "
(SELECT
	time.$fieldEvent AS time,
	COUNT(IF(email.end_time NOT BETWEEN 1 AND time.$fieldEvent, 1, NULL)) AS not_sent,
	COUNT(IF(email.end_time BETWEEN 1 AND time.$fieldEvent AND email.open_time NOT BETWEEN 1 AND time.$fieldEvent AND email.bounce_time NOT BETWEEN 1 AND time.$fieldEvent , email.end_time, NULL)) AS sent,
	COUNT(IF(email.open_time BETWEEN 1 AND time.$fieldEvent AND email.bounce_time NOT BETWEEN 1 AND time.$fieldEvent, email.open_time, NULL)) AS opened,
	COUNT(IF(email.bounce_time BETWEEN 1 AND time.$fieldEvent, email.bounce_time, NULL)) AS bounced,
	COUNT(*) AS total
FROM `tx_newsletter_domain_model_email` AS time
JOIN `tx_newsletter_domain_model_email` AS email ON (email.newsletter = $uidNewsletter)
WHERE
	time.newsletter = $uidNewsletter AND 
	time.$fieldEvent != 0
GROUP BY time.uid)";
		}
		$union = join(' UNION ', $union);
		$query = "SELECT DISTINCT * FROM ($union) AS tmp ORDER BY time ASC";
		
		$rs = $TYPO3_DB->sql_query($query);
		
		$totalEmailCount = $this->getCount($uidNewsletter);
		$result = array();	
		while ($row = $TYPO3_DB->sql_fetch_assoc($rs))
		{	
			// Compute percentage
			foreach (array('not_sent', 'sent', 'opened', 'bounced') as $status)
			{
				$row[$status . '_percentage'] = $row[$status] / $totalEmailCount * 100;
			}

			$niceTime = date('Y-m-d H:i:s', $row['time']);
			$row['time'] = $niceTime;
			$result[$niceTime] = $row;
		}
		
		return $result;
	}
}
