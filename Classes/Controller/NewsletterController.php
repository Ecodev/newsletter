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
 * Controller for the Newsletter object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Controller_NewsletterController extends Tx_MvcExtjs_MVC_Controller_ExtDirectActionController {

	/**
	 * API of $this->pageRendererObject can be found at
	 * http://ecodev.ch/api/typo3/html/classt3lib___page_renderer.html
	 *
	 * @var t3lib_PageRenderer
	 */
	protected $pageRendererObject;

	/**
	 * API of $this->doc can be found at
	 * http://ecodev.ch/api/typo3/html/classtemplate.html
	 *
	 * @var template
	 */
	public $doc;

	/**
	 * the relative javascript path
	 *
	 * @var string
	 */
	public $javascriptPath;

	/**
	 * the relative stylesheet path
	 *
	 * @var string
	 */
	public $stylesheetsPath;


	/**
	 * the page info
	 *
	 * @var array
	 */
	public $pageinfo;

	/**
	 * the page info
	 *
	 * @var Tx_Newsletter_Domain_Repository_StatisticRepository
	 */
	public $statisticRepository;

	/**
	 * newsletterRepository
	 * 
	 * @var Tx_Newsletter_Domain_Repository_NewsletterRepository
	 */
	protected $newsletterRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');

		global $LANG;

		// Needs to be done for compatibility issues
		$GLOBALS['SOBE']->doc = t3lib_div::makeInstance('template');
		
		$this->statisticRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_StatisticRepository');

		#$this->blogRepository = t3lib_div::makeInstance('Tx_BlogExample_Domain_Repository_BlogRepository');
		#$this->administratorRepository = t3lib_div::makeInstance('Tx_BlogExample_Domain_Repository_AdministratorRepository');

		// Language inclusion
		$LANG->includeLLFile("EXT:newsletter/Resources/Private/Language/locallang.xml");

		// Initilize properties
		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $BACK_PATH;
		$this->pageRendererObject = $this->doc->getPageRenderer();

		// Defines CSS + Javascript resource file
		$this->javascriptPath = t3lib_extMgm::extRelPath('newsletter') . 'Resources/Public/JavaScript/';
		$this->stylesheetsPath = t3lib_extMgm::extRelPath('newsletter') . 'Resources/Public/stylesheets/';
		$this->imagePath = t3lib_extMgm::extRelPath('newsletter') . 'Resources/Public/Images/';

		// Get page info
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);

		// Set default value to id
		$this->id = filter_var(t3lib_div::_GET('id'), FILTER_VALIDATE_INT, array("min_range"=> 0));
		if (!$this->id) {
			$this->id = 0;
		}
	}
	/**
	 * Returns a list of newsletters as JSON.
	 * 
	 * @return string The rendered view
	 */
	public function indexAction() {
		global $LANG;

		$this->loadStylesheets();
		$this->loadJavascript();
		$docHeaderButtons = $this->getDocHeaderButtons();

		$markers = array();
		$markers['CSH'] = '';
		$markers['FUNC_MENU'] = '';
		$markers['LOADING'] = $LANG->getLL('loading');

		// Access check!
		if ($this->id)   {
			$markers['FUNC_MENU'] = '<div id="t3-newsletter-menu"></div>';
			$markers['CONTENT'] = '<div id="t3-newsletter-application"></div>';
		}
		else {
			$markers['CONTENT'] = $LANG->getLL('select_page');
		}

		// Configures the page
		$this->doc->setModuleTemplate('EXT:newsletter/Resources/Private/Templates/index.html');
		$this->doc->getContextMenuCode(); // Setting up the context sensitive menu:

		// Generates the HTML
		$this->content = $this->doc->startPage($LANG->getLL('title'));
		$this->content .= $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);
		$this->content .= $this->doc->endPage();
		echo $this->content;
	}

	/**
	 * get doc header buttons
	 *
	 * @global Language $LANG
	 * @return array
	 */
	protected function getDocHeaderButtons() {
		global $LANG;

		// reload icon
		$reloadLanguage = $LANG->sL('LLL:EXT:lang/locallang_core.php:labels.reload', TRUE);
		$spriteIcon = t3lib_iconWorks::getSpriteIcon('actions-system-refresh');
		$markers['reload'] = '<a href="' . $GLOBALS['_SERVER']['REQUEST_URI'] . '" title="' . $reloadLanguage . '">' . $spriteIcon . '</a>';

		// shortcut icon
		$markers['shortcut'] = '';
		if ($GLOBALS['BE_USER']->mayMakeShortcut())	{
			$markers['shortcut'] = $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), 'web_list');
		}

		return $markers;
	}

	/**
	 * Return labels in the form of an array
	 *
	 * @global Language $LANG
	 * @global array $LANG_LANG
	 * @return array
	 */
	protected function getLabels() {
		global $LANG;
		global $LOCAL_LANG;

		if (isset($LOCAL_LANG[$LANG->lang]) && !empty($LOCAL_LANG[$LANG->lang])) {
			$markers = $LOCAL_LANG[$LANG->lang];
			//$markers = $LANG->includeLLFile('EXT:devlog/Resources/Private/Language/locallang.xml', 0);
		}
		else {
			throw new Exception('No language file has been found', 1276451853);
		}
		return $markers;
	}

	/**
	 * Load Javascript files onto the BE Module
	 *
	 * @return void
	 */
	protected function loadStylesheets() {
		$this->pageRendererObject->addCssFile($this->stylesheetsPath . 'newsletter.css');
	}

	/**
	 * Load Javascript files onto the BE Module
	 *
	 * @return void
	 */
	protected function loadJavascript() {

		// *********************************** //
		// Load ExtCore library
		$this->pageRendererObject->loadExtJS();
		$this->pageRendererObject->enableExtJsDebug();

		// *********************************** //
		// Defines what files should be loaded and loads them
		$files = array();
		$files[] = 'Utils.js';

		// Application
		$files[] = 'Application.js';
		$files[] = 'Application/MenuRegistry.js';
		$files[] = 'Application/AbstractBootstrap.js';

		// Override
		$files[] = 'Override/Chart.js';

		// Store
		$files[] = 'Store/Bootstrap.js';
		$files[] = 'Store/StatisticStore.js';
		$files[] = 'Store/NewsletterListStore.js';
		$files[] = 'Store/OverviewPieChartStore.js';
		$files[] = 'Store/ClickedLinkStore.js';
		$files[] = 'Store/SentEmailStore.js';

		// User interfaces
		$files[] = 'UserInterface/Bootstrap.js';
		$files[] = 'UserInterface/ContentArea.js';
		$files[] = 'UserInterface/SectionMenu.js';

		// Newsletter Planner
		$files[] = 'Planner/Bootstrap.js';
		$files[] = 'Planner/PlannerForm.js';
		#$files[] = 'Planner/PlannerForm/PlannerTab.js';
		#$files[] = 'Planner/PlannerForm/SettingsTab.js';
		#$files[] = 'Planner/PlannerForm/StatusTab.js';

		// Statistics
		$files[] = 'Statistics/Bootstrap.js';
		$files[] = 'Statistics/ModuleContainer.js';
		$files[] = 'Statistics/NoStatisticsPanel.js';
		$files[] = 'Statistics/StatisticsPanel.js';
		$files[] = 'Statistics/NewsletterListMenu.js';
		$files[] = 'Statistics/StatisticsPanel/OverviewTab.js';
		$files[] = 'Statistics/StatisticsPanel/LinkTab.js';
		$files[] = 'Statistics/StatisticsPanel/LinkTab/LinkGrid.js';
		$files[] = 'Statistics/StatisticsPanel/LinkTab/LinkGraph.js';
		$files[] = 'Statistics/StatisticsPanel/EmailTab.js';
		$files[] = 'Statistics/StatisticsPanel/EmailTab/EmailGrid.js';
		$files[] = 'Statistics/StatisticsPanel/EmailTab/EmailGraph.js';
		$files[] = 'Statistics/StatisticsPanel/OverviewTab/General.js';
//		$files[] = 'Statistics/StatisticsPanel/OverviewTab/Graph.js';

		foreach ($files as $file) {
			$this->pageRendererObject->addJsFile($this->javascriptPath . $file, 'text/javascript', FALSE);
		}

		// Add ExtJS API
		$this->pageRendererObject->addJsFile('ajax.php?ajaxID=ExtDirect::getAPI&namespace=Ext.ux.TYPO3.Newsletter', 'text/javascript', FALSE);

		$numberOfStatistics = json_encode($this->statisticRepository->countStatistics($this->id));
		
		// *********************************** //
		// Defines onready Javascript
		$this->readyJavascript = array();
		$this->readyJavascript[] .= <<< EOF

			Ext.ns("Ext.ux.TYPO3.Newsletter.Data");
			Ext.ux.TYPO3.Newsletter.Data.numberOfStatistics = $numberOfStatistics;
			Ext.ux.TYPO3.Newsletter.Data.imagePath = '$this->imagePath';

		// Enable our remote calls
		for (var api in Ext.app.ExtDirectAPI) {
			Ext.Direct.addProvider(Ext.app.ExtDirectAPI[api]);
		}
EOF;

		$this->pageRendererObject->addExtOnReadyCode(PHP_EOL . implode("\n", $this->readyJavascript) . PHP_EOL);

		// *********************************** //
		// Defines contextual variables
		$labels = json_encode($this->getLabels());
		$parameters = json_encode(array('pid' => $this->id));

		$this->inlineJavascript[] .= <<< EOF

		Ext.ns("Ext.ux.TYPO3.Newsletter");
		Ext.ux.TYPO3.Newsletter.Language = $labels;

		Ext.ns("TYPO3.Devlog.Data");
		TYPO3.Devlog.Data.Parameters = $parameters;

EOF;
		$this->pageRendererObject->addJsInlineCode('newsletter', implode("\n", $this->inlineJavascript));
	}
	
	
		
	/**
	 * Displays all Newsletters
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$newsletters = $this->newsletterRepository->findAll();
		
		if(count($newsletters) < 1){
			$settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
			if(empty($settings['persistence']['storagePid'])){
				$this->flashMessageContainer->add('No storagePid configured!');
			}
		}
		
		$this->view->assign('newsletters', $newsletters);
	}
	
		
	/**
	 * Displays a single Newsletter
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->view->assign('newsletter', $newsletter);
	}
	
		
	/**
	 * Creates a new Newsletter and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newNewsletter a fresh Newsletter object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Newsletter
	 * @dontvalidate $newNewsletter
	 */
	public function newAction(Tx_Newsletter_Domain_Model_Newsletter $newNewsletter = NULL) {
		$this->view->assign('newNewsletter', $newNewsletter);
	}
	
		
	/**
	 * Creates a new Newsletter and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newNewsletter a fresh Newsletter object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Newsletter_Domain_Model_Newsletter $newNewsletter) {
		$this->newsletterRepository->add($newNewsletter);
		$this->flashMessageContainer->add('Your new Newsletter was created.');
		
			
		
		$this->redirect('list');
	}
	
		
	
	/**
	 * Updates an existing Newsletter and forwards to the index action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to display
	 * @return string A form to edit a Newsletter 
	 */
	public function editAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->view->assign('newsletter', $newsletter);
	}
	
		

	/**
	 * Updates an existing Newsletter and forwards to the list action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to display
	 */
	public function updateAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->newsletterRepository->update($newsletter);
		$this->flashMessageContainer->add('Your Newsletter was updated.');
		$this->redirect('list');
	}
	
		
			/**
	 * Deletes an existing Newsletter
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->newsletterRepository->remove($newsletter);
		$this->flashMessageContainer->add('Your Newsletter was removed.');
		$this->redirect('list');
	}
	

}
?>