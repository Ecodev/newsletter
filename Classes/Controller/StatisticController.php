<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Fabien Udriot <fabien.udriot@ecodev.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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
 * The statistic controller.
 *
 * @category    Controller
 * @package     TYPO3
 * @subpackage  tx_newsletter
 * @author      Fabien Udriot <fabien.udriot@ecodev.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_Newsletter_Controller_StatisticController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_Newsletter_Domain_Repository_StatisticRepository
	 */
	protected $statisticRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->statisticRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_StatisticRepository');
	}

	/**
	 * Returns a list of statistics.
	 * 
	 * @param int $pid: the pid where newsletter are stored
	 * @return string The rendered view
	 */
	public function indexAction($pid) {
		$statistics = $this->statisticRepository->findAllByPid($pid);
		$this->view->assign('statistics', $statistics);
		$this->view->assign('metaData', $this->statisticRepository->getMetaDataForMultipleRecords());
	}

	/**
	 * Returns statistics for one newsletter.
	 *
	 * @param int $uid: the newsletter's id
	 * @return string The rendered view
	 */
	public function showAction($uid) {
		$statistic = $this->statisticRepository->findByUid($uid);
		$this->view->assign('statistic', $statistic);
		$this->view->assign('metaData', $this->statisticRepository->getMetaDataForSingleRecord());
	}
}
?>