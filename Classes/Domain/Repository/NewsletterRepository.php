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
 * Repository for Tx_Newsletter_Domain_Model_Newsletter
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
 
class Tx_Newsletter_Domain_Repository_NewsletterRepository extends Tx_Newsletter_Domain_Repository_AbstractRepository {
	
	/**
	 * Returns the latest newsletter for the given page
	 * @param integer $pid
	 */
	public function getLatest($pid)
	{
		$query = $this->createQuery();
		$query->setLimit(1);
		$query->matching($query->equals('pid', $pid));
		
		$query->setOrderings(array('uid' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING));
		
		return $query->execute()->getFirst();
	}
	
	public function findAllByPid($pid)
	{
		if ($pid < 1)
			return $this->findAll();
		
		$query = $this->createQuery();
		$query->matching($query->equals('pid', $pid));
		
		$query->setOrderings(array('uid' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING));
		
		return $query->execute();
	}
	
	/**
	 * Returns all newsletter which are ready to be sent now and not yet locked (sending already started)
	 * @param boolean $onlyTest
	 * @return Tx_Newsletter_Domain_Model_Newsletter[] 
	 */
	public function findAllReadyToSend($onlyTest = false)
	{
		if ($onlyTest)
			$onlyTest = 'AND is_test = 1 ';
		else
			$onlyTest = ' ';

		$query = $this->createQuery();
		$query->statement("SELECT * 
		                              FROM tx_newsletter_domain_model_newsletter 
		                              WHERE planned_time <= " . time() . "
		                              AND planned_time <> 0 
		                              AND begin_time = 0
		                              AND deleted = 0
		                              AND hidden = 0
		                              $onlyTest
		                              ");
		
		return $query->execute();
	}

	/**
	 * Returns newsletter statistics to be used for pie and timeline chart
	 * We will get the full state for each timestep when something happened
	 * @param integer $uidNewsletter 
	 * @return array eg: array(array(time, emailNotSentCount, emailSentCount, emailOpenedCount, emailBouncedCount, emailCount, linkOpenedCount, linkCount, [and same fields but Percentage instead of Count] ))
	 */
	public function getStatistics($uidNewsletter) {
		global $TYPO3_DB;
		$uidNewsletter = (int)$uidNewsletter;
	
		// This sub-subquery is used to find every single timestep when something happened (email sent, opened, bounced or link opened)
		$timestep = "(
		(SELECT planned_time AS time FROM `tx_newsletter_domain_model_newsletter` WHERE uid = $uidNewsletter)
		UNION
		(SELECT end_time AS time FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND end_time)
		UNION
		(SELECT open_time AS time FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND open_time)
		UNION
		(SELECT bounce_time AS time FROM `tx_newsletter_domain_model_email` WHERE newsletter = $uidNewsletter AND bounce_time)
		UNION
		(SELECT time.open_time AS time FROM `tx_newsletter_domain_model_linkopened` AS time INNER JOIN `tx_newsletter_domain_model_email` AS email ON (time.email = email.uid AND email.newsletter = $uidNewsletter) WHERE time.open_time)
	) AS time";
		
		// SQL query which will retrieve statistics for all emails and links everytime an event happened to one email (sent, opened, or bounced) or one link (opened)
		// So in one (big) query, we get each step of the complete history of the newsletter
		$query = "-- This outer-query will join results from email, opened links and total links statistics
SELECT email.time AS time, emailNotSentCount, emailSentCount, emailOpenedCount, emailBouncedCount, emailCount, linkOpenedCount, linkCount
FROM
	-- This first subquery will count email status for each timestep found
	(SELECT
		time,
		COUNT(DISTINCT IF(email.end_time NOT BETWEEN 1 AND time.time, email.uid, NULL)) AS emailNotSentCount,
		COUNT(DISTINCT IF(email.end_time BETWEEN 1 AND time.time AND email.open_time NOT BETWEEN 1 AND time.time AND email.bounce_time NOT BETWEEN 1 AND time.time , email.uid, NULL)) AS emailSentCount,
		COUNT(DISTINCT IF(email.open_time BETWEEN 1 AND time.time AND email.bounce_time NOT BETWEEN 1 AND time.time, email.uid, NULL)) AS emailOpenedCount,
		COUNT(DISTINCT IF(email.bounce_time BETWEEN 1 AND time.time, email.uid, NULL)) AS emailBouncedCount,
		COUNT(DISTINCT email.uid) AS emailCount
	FROM $timestep
	JOIN `tx_newsletter_domain_model_email` AS email ON (email.newsletter = $uidNewsletter)
	GROUP BY time.time)
AS email

JOIN 
	-- This second subquery will count opened links for each timestep found
	(SELECT
		time,
		COUNT(DISTINCT IF(linkopened.open_time BETWEEN 1 AND time.time, linkopened.uid, NULL)) AS linkOpenedCount
	FROM $timestep
	JOIN `tx_newsletter_domain_model_email` AS email_linkopened ON (email_linkopened.newsletter = $uidNewsletter)
	LEFT JOIN `tx_newsletter_domain_model_linkopened` AS linkopened ON (linkopened.email = email_linkopened.uid)
	GROUP BY time.time)
AS linkopened ON (email.time = linkopened.time)

JOIN
	-- This third and final subquery will just count the total of links (not time dependent, so always same total)
	(SELECT
		COUNT(*) AS linkCount
	FROM `tx_newsletter_domain_model_link` AS link WHERE link.newsletter = $uidNewsletter)
AS linkCount
ORDER BY email.time ASC
";
		
		$result = array();
		$rs = $TYPO3_DB->sql_query($query);
		while ($row = $TYPO3_DB->sql_fetch_assoc($rs))
		{	
			// Compute percentage
			foreach (array('emailNotSent', 'emailSent', 'emailOpened', 'emailBounced') as $status)
			{
				$row[$status . 'Percentage'] = $row[$status . 'Count'] / $row['emailCount'] * 100;
			}
			$row['linkOpenedPercentage'] = $row['linkOpenedCount'] / ($row['linkCount'] * $row['emailCount']) * 100;
			
			$result[] = $row;
		}
		
		return $result;
	}	
}
