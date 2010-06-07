<?php
unset($MCONF);   
define('TYPO3_MODE','FE');
define('TYPO3_PROCEED_IF_NO_USER', TRUE);
define('PATH_thisScript', $_SERVER['SCRIPT_FILENAME']);

require('conf.php');
require($BACK_PATH.'init.php');
?>
