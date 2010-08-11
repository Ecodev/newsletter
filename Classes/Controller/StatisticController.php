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
	 * Returns a list of statistics as JSON.
	 * 
	 * @return string The rendered view
	 */
	public function indexAction() {

		/* @var $statisticRepository Tx_MvcExtjsSamples_Domain_Repository_GenreRepository */
		$statisticRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_StatisticRepository');

		$statistic = new Tx_Newsletter_Domain_Model_Statistic();

		#$arguments = $this->request->getArguments();
		$pid = filter_var(t3lib_div::_GET('pid'), FILTER_VALIDATE_INT, array("min_range"=> 0));
		if ($pid) {
			$statistic->setPid($pid);
		}
		
		// Retrieve all statistics from repository
		$statistics = $statisticRepository->findByPid($statistic);
		$this->view->assign('statistics', $statistics);
		$this->view->assign('metaData', $statisticRepository->getMetaData());
		#$this->request->getPluginName();
	}
	
}
?>