#! /usr/bin/php -q
<?php
require ("clirun.php");
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_bouncehandler.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$fd = fopen('php://stdin', 'r');
while ($buffer = fread($fd, 8096)) {
   $content .= $buffer;
}
fclose($fd);

$bounce = new tx_newsletter_bouncehandler($content);


switch ($bounce->status) {
	case NEWSLETTER_HARDBOUNCE :
	case NEWSLETTER_SOFTBOUNCE :
		$target = tx_newsletter_target::getTarget($bounce->targetUid);
		$target->disableReceiver($bounce->uid, $bounce->status);
	

	case NEWSLETTER_BOUNCE_UNREMOVABLE:
	$TYPO3_DB->exec_UPDATEquery('tx_newsletter_sentlog', 
					"authcode = '$bounce->authCode' AND uid = '$bounce->sendid'", 
					array('bounced' => 1));
		break;
	default:
	/* Nothing to be done for other bounce types. */
	break;
}
?>
