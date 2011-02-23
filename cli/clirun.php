<?php
define('TYPO3_cliMode', TRUE);
define('TYPO3_PROCEED_IF_NO_USER', TRUE);
define('PATH_thisScript', __FILE__);

require_once(dirname(PATH_thisScript).'/conf.php');
require_once(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_tools.php");
