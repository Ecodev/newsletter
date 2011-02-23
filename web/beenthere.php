<?php
/**
 * This is the htmlmail-opened-ping script, that detects if a user has opened the mail
 */
 
$authcode = addslashes($_REQUEST['c']);
$sendid = intval($_REQUEST['s']); 

require ('browserrun.php');
require (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_target.php');
require (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');


// Record that the email was opened
$TYPO3_DB->sql_query("UPDATE tx_newsletter_domain_model_email SET opened = 1 WHERE MD5(CONCAT(uid, recipient_address)) = '$authcode'");

// TODO clean up the registerOpen for targets so it still wokrs (based on email address instead of cumbersome authcode+uid ?)
// Tell the target that he opened the email
$rs = $TYPO3_DB->sql_query("SELECT target, user_uid FROM tx_newsletter_domain_model_email WHERE MD5(CONCAT(uid, recipient_address)) = '$authcode'");
if (list($targetUid, $userUid) = $TYPO3_DB->sql_fetch_row($rs)) {
	$target = tx_newsletter_target::getTarget($targetUid);
	$target->registerOpen($userUid);
}

header ('Content-type: image/gif');
readfile ('clear.gif');
