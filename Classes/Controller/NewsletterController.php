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
 * The newsletter controller.
 *
 * @category    Controller
 * @package     TYPO3
 * @subpackage  tx_newsletter
 * @author      Fabien Udriot <fabien.udriot@ecodev.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_Newsletter_Controller_NewsletterController extends Tx_Extbase_MVC_Controller_ActionController {

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
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		global $LANG;

		// Needs to be done for compatibility issues
		$GLOBALS['SOBE']->doc = t3lib_div::makeInstance('template');

		#$this->blogRepository = t3lib_div::makeInstance('Tx_BlogExample_Domain_Repository_BlogRepository');
		#$this->administratorRepository = t3lib_div::makeInstance('Tx_BlogExample_Domain_Repository_AdministratorRepository');

		// Language inclusion
		$LANG->includeLLFile("EXT:newsletter/Resources/Private/Language/locallang.xml");

		// Initilize properties
		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $BACK_PATH;
		$this->pageRendererObject = $this->doc->getPageRenderer();

		// Defines CSS + Javascript resource file
		$this->javascriptPath = t3lib_extMgm::extRelPath('newsletter') . 'Resources/Public/javascripts/';
		$this->stylesheetsPath = t3lib_extMgm::extRelPath('newsletter') . 'Resources/Public/stylesheets/';

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


		#$newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_newsletterRepository');
		/* @var $newsletterRepository Tx_Newsletter_Domain_Repository_newsletterRepository */
		
			// Retrieve all newsletters from repository
		#$newsletters = $newsletterRepository->findAll();
		
		#$this->view->assign('newsletters', $newsletters);
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
			throw new tx_devlog_exception('No language file has been found', 1276451853);
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

		// Store
		$files[] = 'Store/Bootstrap.js';
		$files[] = 'Store/StatisticsStore.js';
		$files[] = 'Store/NewsletterListStore.js';
		$files[] = 'Store/OverviewPieChartStore.js';

		// User interfaces
		$files[] = 'UserInterface/Bootstrap.js';
		$files[] = 'UserInterface/ContentArea.js';
		$files[] = 'UserInterface/SectionMenu.js';

		// Newsletter Planner
		$files[] = 'Planner/Bootstrap.js';
		$files[] = 'Planner/PlannerForm.js';
		$files[] = 'Planner/PlannerForm/PlannerTab.js';
		$files[] = 'Planner/PlannerForm/SettingsTab.js';
		$files[] = 'Planner/PlannerForm/StatusTab.js';

		// Statistics
		$files[] = 'Statistics/Bootstrap.js';
		$files[] = 'Statistics/ModuleContainer.js';
		$files[] = 'Statistics/StatisticsPanel.js';
		$files[] = 'Statistics/NewsletterListMenu.js';
		$files[] = 'Statistics/StatisticsPanel/OverviewTab.js';
		$files[] = 'Statistics/StatisticsPanel/LinkTab.js';
		$files[] = 'Statistics/StatisticsPanel/EmailTab.js';
		$files[] = 'Statistics/StatisticsPanel/OverviewTab/General.js';
//		$files[] = 'Statistics/StatisticsPanel/OverviewTab/Graph.js';

		foreach ($files as $file) {
			$this->pageRendererObject->addJsFile($this->javascriptPath . $file, 'text/javascript', FALSE);
		}

		// Add ExtJS API
		$this->pageRendererObject->addJsFile('ajax.php?ajaxID=ExtDirect::getAPI&namespace=TYPO3.Newsletter', 'text/javascript', FALSE);


		// *********************************** //
		// Defines onready Javascript
		$this->readyJavascript = array();
		$this->readyJavascript[] .= <<< EOF

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

		Ext.chart.Chart.CHART_URL = 'http://newsletter.local/typo3/contrib/extjs/resources/charts.swf';

		Ext.ns("TYPO3.Newsletter");
		TYPO3.Newsletter.Language = $labels;

		Ext.ns("TYPO3.Devlog.Data");
		TYPO3.Devlog.Data.Parameters = $parameters;

EOF;
		$this->pageRendererObject->addJsInlineCode('newsletter', implode("\n", $this->inlineJavascript));
	}

	
}
?>