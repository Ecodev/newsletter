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
	 * @param integer $uidNewsletter
	 */
	public function getCount($uidNewsletter)
	{
		global $TYPO3_DB;
		$count = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_link', 'newsletter = ' . $uidNewsletter);

		return (int)$count;
	}
}
