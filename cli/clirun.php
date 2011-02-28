<?php

// DO NOT REMOVE OR CHANGE THESE 3 LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/newsletter/cli/');
$BACK_PATH = '../../../../typo3/';
$MCONF['name'] = '_CLI_newsletter';

define('TYPO3_cliMode', TRUE);
define('TYPO3_PROCEED_IF_NO_USER', TRUE);
define('PATH_thisScript', __FILE__);

require_once(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_tools.php");
