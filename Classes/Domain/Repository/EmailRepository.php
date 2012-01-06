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

	protected static $emailCountCache = array();
	
	/**
	 * Returns the email corresponsding to the authCode
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
		// If we have cached result return directly that value to avoid X query for X Links per newsletter
		if (isset(self::$emailCountCache[$uidNewsletter]))
		{
			return self::$emailCountCache[$uidNewsletter];
		}
		
		global $TYPO3_DB;
		$count = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'newsletter = ' . $uidNewsletter);
		self::$emailCountCache[$uidNewsletter] = $count;
		
		return (int)$count;
	}
	
	/**
	 * Returns all email for a given newsletter
	 * @param integer $uidNewsletter
	 * @param integer $start
	 * @param integer $limit
	 * @return Tx_Newsletter_Domain_Model_Email[] 
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
}
