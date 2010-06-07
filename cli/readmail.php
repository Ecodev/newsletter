#! /usr/bin/php -q
<?php
require ("clirun.php");
require_once(t3lib_extMgm::extPath('tcdirectmail').'class.tx_tcdirectmail_bouncehandler.php');
require_once(t3lib_extMgm::extPath('tcdirectmail').'class.tx_tcdirectmail_tools.php');

$fd = fopen('php://stdin', 'r');
while ($buffer = fread($fd, 8096)) {
   $content .= $buffer;
}
fclose($fd);

$bounce = new tx_tcdirectmail_bouncehandler($content);


switch ($bounce->status) {
	case TCDIRECTMAIL_HARDBOUNCE :
	case TCDIRECTMAIL_SOFTBOUNCE :
		$target = tx_tcdirectmail_target::getTarget($bounce->targetUid);
		$target->disableReceiver($bounce->uid, $bounce->status);
	

	case TCDIRECTMAIL_BOUNCE_UNREMOVABLE:
	$TYPO3_DB->exec_UPDATEquery('tx_tcdirectmail_sentlog', 
					"authcode = '$bounce->authCode' AND uid = '$bounce->sendid'", 
					array('bounced' => 1));
		break;
	default:
	/* Nothing to be done for other bounce types. */
	break;
}
?>
