<?php
/**
 * This is the click link script that identifies and registers the user, and provides the correct link
 */
require ('browserrun.php');
require (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_target.php');
require (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$authcode = addslashes($_REQUEST['c']);
$linkid = intval($_REQUEST['l']);
$linktype = addslashes($_REQUEST['t']);
$sendid = intval($_REQUEST['s']);

$where_clause = "WHERE authcode = '$authcode' AND linkid = $linkid AND uid = $sendid AND linktype = '$linktype'";

/* Register this link */ 
$TYPO3_DB->sql_query("UPDATE tx_newsletter_domain_model_clicklink 
                       INNER JOIN tx_newsletter_domain_model_emailqueue ON tx_newsletter_domain_model_clicklink.sentlog = tx_newsletter_domain_model_emailqueue.uid
                       SET opened = 1 $where_clause"); 

/* Register the user */
$TYPO3_DB->sql_query("UPDATE tx_newsletter_domain_model_emailqueue SET beenthere = 1 WHERE authcode = '$authcode' AND uid = $sendid");


$rs = $TYPO3_DB->sql_query("SELECT target, user_uid FROM tx_newsletter_domain_model_emailqueue WHERE authcode = '$authcode' AND uid = $sendid");
if (list($targetUid, $userUid) = $TYPO3_DB->sql_fetch_row($rs)) {
	$target = tx_newsletter_target::getTarget($targetUid);
	$target->registerClick($userUid);
}



/* Deliver the real url */
$rs = $TYPO3_DB->sql_query("SELECT url FROM tx_newsletter_domain_model_clicklink 
                            INNER JOIN tx_newsletter_domain_model_emailqueue ON tx_newsletter_domain_model_clicklink.sentlog = tx_newsletter_domain_model_emailqueue.uid
                            $where_clause");
                              
list($url) = $TYPO3_DB->sql_fetch_row($rs);
header ("Location: $url");
?>
