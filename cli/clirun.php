<?php
define('TYPO3_cliMode', TRUE);
define('TYPO3_PROCEED_IF_NO_USER', TRUE);
define('PATH_thisScript',trim($GLOBALS['argv'][0]));
require(dirname(PATH_thisScript).'/conf.php');
require(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');
?>
