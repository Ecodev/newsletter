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
	
}
