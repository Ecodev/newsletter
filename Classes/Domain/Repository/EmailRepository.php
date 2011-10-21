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
		$rs = $TYPO3_DB->sql_query($query = "
			SELECT ((MIN(min))) AS min, ((MAX(max))) AS max FROM
			(
				(SELECT DISTINCT MIN(bounce_time) AS min,  MAX(bounce_time) AS max FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND bounce_time)
				UNION
				(SELECT DISTINCT MIN(open_time) AS min, MAX(open_time) AS max FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND open_time)
				UNION
				(SELECT DISTINCT MIN(end_time) AS min, MAX(end_time) AS max FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND end_time)
			) AS tmp
			");
		
		$values = $TYPO3_DB->sql_fetch_assoc($rs);
		$min = $values['min'];
		$max = $values['max'];
		$min = strtotime(date('Y-m-d H:00:00', $min)); // Round down to the hour
		$max = strtotime(date('Y-m-d H:00:00', strtotime('+1 hour', $max))); // Round up to the hour
		
		$result = array();
		foreach (range($min, $max, 60 * 60) as $time)
		{
			
			$query = "
				SELECT
					COUNT(*) AS count, 
					IF(bounce_time AND bounce_time < $time, 'bounced', IF(open_time AND open_time < $time, 'opened', IF(end_time AND end_time < $time, 'sent', 'not_sent'))) AS status
				FROM `tx_newsletter_domain_model_email`
				WHERE newsletter = $uidNewsletter
				GROUP BY status";
			$rs = $TYPO3_DB->sql_query($query);
			
			$niceTime = date('Y-m-d H:00:00', $time);
			while ($row = $TYPO3_DB->sql_fetch_assoc($rs))
			{
				$result[$niceTime]['time'] = $niceTime;
				$result[$niceTime][$row['status']] = $row['count'];
			}
		}
		//var_dump($result);
		return $result;
	}
}
