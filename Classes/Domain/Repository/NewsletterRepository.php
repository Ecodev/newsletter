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
	 * We will get the full state for each time when something happened
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter
	 * @return array eg: array(array(time, emailNotSentCount, emailSentCount, emailOpenedCount, emailBouncedCount, emailCount, linkOpenedCount, linkCount, [and same fields but Percentage instead of Count] ))
	 */
	public function getStatistics(Tx_Newsletter_Domain_Model_Newsletter $newsletter)
	{
		$uidNewsletter = $newsletter->getUid();

		$stateDifferences = array();
		$emailCount = $this->fillStateDifferences(
			$stateDifferences,
			'tx_newsletter_domain_model_email',
			'newsletter = ' . $uidNewsletter,
			array(
				'end_time' => array('increment' => 'emailSentCount', 'decrement' => 'emailNotSentCount'),
				'open_time' => array('increment' => 'emailOpenedCount', 'decrement' => 'emailSentCount'),
				'bounce_time' => array('increment' => 'emailBouncedCount', 'decrement' => 'emailSentCount'),
			)
		);

		$linkRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_LinkRepository');
		$linkCount = $linkRepository->getCount($uidNewsletter);
		$this->fillStateDifferences(
			$stateDifferences,
			'tx_newsletter_domain_model_link LEFT JOIN tx_newsletter_domain_model_linkopened ON (tx_newsletter_domain_model_linkopened.link = tx_newsletter_domain_model_link.uid)',
			'tx_newsletter_domain_model_link.newsletter = ' . $uidNewsletter,
			array(
				'open_time' => array('increment' => 'linkOpenedCount'),
			)
		);

		// Find out the very first event (when the newsletter was planned)
		$plannedTime = $newsletter ? $newsletter->getPlannedTime() : null;
		$emailCount = $newsletter ? $newsletter->getEmailCount() : $emailCount; // We re-calculate email count so get correct number if newsletter is not sent yet
		$previousState = array(
			'time' => $plannedTime ? (int)$plannedTime->format('U') : null,
			'emailNotSentCount' => $emailCount,
			'emailSentCount' => 0,
			'emailOpenedCount' => 0,
			'emailBouncedCount' => 0,
			'emailCount' => $emailCount,
			'linkOpenedCount' => 0,
			'linkCount' => $linkCount,
			'emailNotSentPercentage' => 100,
			'emailSentPercentage' => 0,
			'emailOpenedPercentage' => 0,
			'emailBouncedPercentage' => 0,
			'linkOpenedPercentage' => 0,
		);

		// Find out what the best grouping step is according to number of states
		$stateCount = count($stateDifferences);
		if ($stateCount > 5000)
			$groupingTimestep =  15 * 60; // 15 minutes
		elseif ($stateCount > 500)
			$groupingTimestep =  5 * 60; // 5 minutes
		elseif ($stateCount > 50)
			$groupingTimestep =  1 * 60; // 1 minutes
		else
			$groupingTimestep = 0; // no grouping at all

		$states = array($previousState);
		ksort($stateDifferences);
		$minimumTimeToInsert = 0; // First state must always be not grouped, so we don't increment here
		foreach ($stateDifferences as $time => $diff)
		{
			$newState = $previousState;
			$newState['time'] = $time;

			// Apply diff to previous state to get new state's absolute values
			foreach ($diff as $key => $value)
			{
				$newState[$key] += $value;
			}

			// Compute percentage for email states
			foreach (array('emailNotSent', 'emailSent', 'emailOpened', 'emailBounced') as $key)
			{
				$newState[$key . 'Percentage'] = $newState[$key . 'Count'] / $newState['emailCount'] * 100;
			}

			// Compute percentage for link states
			if ($newState['linkCount'] && $newState['emailCount'])
				$newState['linkOpenedPercentage'] = $newState['linkOpenedCount'] / ($newState['linkCount'] * $newState['emailCount']) * 100;
			else
				$newState['linkOpenedPercentage'] = 0;

			// Insert the state only if grouping allows it
			if ($time >= $minimumTimeToInsert)
			{
				$states[]= $newState;
				$minimumTimeToInsert = $time + $groupingTimestep;
			}
			$previousState = $newState;
		}

		// Don't forget to always add the very last state, if not already inserted
		if (!($time >= $minimumTimeToInsert))
		{
			$states[]= $newState;
		}

		return $states;
	}

	/**
	 * Fills the $stateDifferences array with incremental difference that the state introduce.
	 * It supports merging with existing diff in the array and several states on the same time.
	 * @global t3lib_DB $TYPO3_DB
	 * @param array $stateDifferences
	 * @param string $from
	 * @param string $where
	 * @param array $stateConfiguration
	 * @return int count of records (not count of states)
	 */
	protected function fillStateDifferences(array &$stateDifferences, $from, $where, array $stateConfiguration)
	{
		$default = array(
			'emailNotSentCount' => 0,
			'emailSentCount' => 0,
			'emailOpenedCount' => 0,
			'emailBouncedCount' => 0,
			'emailBouncedCount' => 0,
			'linkOpenedCount' => 0,
		);

		/** @var $TYPO3_DB t3lib_DB */
		global $TYPO3_DB;

		$rs = $TYPO3_DB->exec_SELECTquery(join(', ', array_keys($stateConfiguration)), $from, $where);
		$count = 0;
		while ($email = $TYPO3_DB->sql_fetch_assoc($rs))
		{
			foreach ($stateConfiguration as $stateKey => $stateConf)
			{
				$time = $email[$stateKey];
				if ($time)
				{
					if (!isset($stateDifferences[$time]))
						$stateDifferences[$time] = $default;

					$stateDifferences[$time][$stateConf['increment']]++;
					if (isset($stateConf['decrement']))
						$stateDifferences[$time][$stateConf['decrement']]--;
				}
			}
			$count++;
		}
		$TYPO3_DB->sql_free_result($rs);

		return $count;
	}
}
