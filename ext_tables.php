<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
	$TBE_MODULES['_dispatcher'][] = $TBE_MODULES['_dispatcher'][0];
	$TBE_MODULES['_dispatcher'][0] = 'Tx_MvcExtjs_Dispatcher';
}

//$GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['concatenateHandler'] = "EXT:mvc_extjs/Classes/PageRenderer/Service.php:&tx_MvcExtjs_PageRenderer_Service->doConcatenate";

?>