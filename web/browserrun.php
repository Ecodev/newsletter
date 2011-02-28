<?php

// DO NOT REMOVE OR CHANGE THESE LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/newsletter/web/');
$BACK_PATH = '../../../../typo3/';
define('TYPO3_MODE', 'FE');
define('TYPO3_PROCEED_IF_NO_USER', TRUE);
define('PATH_thisScript', __FILE__);

require(dirname(__FILE__) . '/../../../../typo3/init.php');


function initTSFE($pageUid = 1, $overrule = FALSE) {
	// declare
	$temp_TSFEclassName = t3lib_div::makeInstanceClassName('tslib_fe');

	// begin
	if (!is_object($GLOBALS['TT']) || $overrule === TRUE) {
		$GLOBALS['TT'] = new t3lib_timeTrack;
		$GLOBALS['TT']->start();
	}

	if ((!is_object($GLOBALS['TSFE']) || $overrule === TRUE) && is_int($pageUid))
	{
		// builds TSFE object
		$GLOBALS['TSFE'] = new $temp_TSFEclassName($GLOBALS['TYPO3_CONF_VARS'],
		$pageUid, $type=0, $no_cache=0, $cHash='', $jumpurl='', $MP='', $RDCT='');

		// builds rootline
		$GLOBALS['TSFE']->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $GLOBALS['TSFE']->sys_page->getRootLine($pageUid);

		// init template
		$GLOBALS['TSFE']->tmpl = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$GLOBALS['TSFE']->tmpl->tt_track = 0;// Do not log time-performance information
		$GLOBALS['TSFE']->tmpl->init();

		// this generates the constants/config + hierarchy info for the template.
		$GLOBALS['TSFE']->tmpl->runThroughTemplates($rootLine, $start_template_uid=0);
		$GLOBALS['TSFE']->tmpl->generateConfig();
		$GLOBALS['TSFE']->tmpl->loaded=1;

		// get config array and other init from pagegen
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->linkVars = ''.$GLOBALS['TSFE']->config['config']['linkVars'];

		if ($GLOBALS['TSFE']->config['config']['simulateStaticDocuments_pEnc_onlyP'])
		{
			foreach (t3lib_div::trimExplode(',',$GLOBALS['TSFE']->config['config']['simulateStaticDocuments_pEnc_onlyP'],1)
			as $temp_p) {
				$GLOBALS['TSFE']->pEncAllowedParamNames[$temp_p]=1;
			}
		}
		// builds a cObj
		$GLOBALS['TSFE']->newCObj();
	}
}
initTSFE();