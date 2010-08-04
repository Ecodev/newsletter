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
//			// Prepare the view
//		$this->masterView = t3lib_div::makeInstance('Tx_Fluid_View_TemplateView');
//		$controllerContext = $this->buildControllerContext();
//		$this->masterView->setControllerContext($controllerContext);
//		$this->masterView->setTemplatePathAndFilename(t3lib_extMgm::extPath('mvc_extjs') . 'Resources/Private/Templates/module.html');
//
//		$this->scBase = t3lib_div::makeInstance('t3lib_SCbase');
//		$this->scBase->MCONF['name'] = $this->request->getPluginName();
//		$this->scBase->init();
//
//			// Prepare template class
//		$this->doc = t3lib_div::makeInstance('template');
//		$this->doc->backPath = $GLOBALS['BACK_PATH'];
//
//		$this->scBase->doc = $this->doc;
//		$this->pageRendererObject = $this->doc->getPageRenderer();
//
//			// Prepare menu and merge other extension module functions
//		$this->toolbar = t3lib_div::makeInstance('Tx_Mvcextjs_ExtJS_Layout_Toolbar', $this, $this->request->getPluginName(), $this->scBase);
//		$this->menuConfig();
//
//		$this->extPath = t3lib_extMgm::extPath($this->request->getControllerExtensionKey());
//		$this->extRelPath = substr($this->extPath, strlen(PATH_site));

		echo 11;
		exit();

		#$statisticRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_statisticRepository');
		/* @var $statisticRepository Tx_Newsletter_Domain_Repository_statisticRepository */
		
			// Retrieve all statistics from repository
		#$statistics = $statisticRepository->findAll();
		
		#$this->view->assign('statistics', $statistics);
	}
	
}
?>