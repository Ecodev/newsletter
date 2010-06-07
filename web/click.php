<?php
/**
 * This is the click link script that identifies and registers the user, and provides the correct link
 */
require ('browserrun.php');
require (t3lib_extMgm::extPath('tcdirectmail').'class.tx_tcdirectmail_target.php');
require (t3lib_extMgm::extPath('tcdirectmail').'class.tx_tcdirectmail_tools.php');

$authcode = addslashes($_REQUEST['c']);
$linkid = intval($_REQUEST['l']);
$linktype = addslashes($_REQUEST['t']);
$sendid = intval($_REQUEST['s']);

$where_clause = "WHERE authcode = '$authcode' AND linkid = $linkid AND uid = $sendid AND linktype = '$linktype'";

/* Register this link */ 
$TYPO3_DB->sql_query("UPDATE tx_tcdirectmail_clicklinks 
                       INNER JOIN tx_tcdirectmail_sentlog ON tx_tcdirectmail_clicklinks.sentlog = tx_tcdirectmail_sentlog.uid
                       SET opened = 1 $where_clause"); 

/* Register the user */
$TYPO3_DB->sql_query("UPDATE tx_tcdirectmail_sentlog SET beenthere = 1 WHERE authcode = '$authcode' AND uid = $sendid");


$rs = $TYPO3_DB->sql_query("SELECT target, user_uid FROM tx_tcdirectmail_sentlog WHERE authcode = '$authcode' AND uid = $sendid");
if (list($targetUid, $userUid) = $TYPO3_DB->sql_fetch_row($rs)) {
	$target = tx_tcdirectmail_target::getTarget($targetUid);
	$target->registerClick($userUid);
}



/* Deliver the real url */
$rs = $TYPO3_DB->sql_query("SELECT url FROM tx_tcdirectmail_clicklinks 
                            INNER JOIN tx_tcdirectmail_sentlog ON tx_tcdirectmail_clicklinks.sentlog = tx_tcdirectmail_sentlog.uid
                            $where_clause");
                              
list($url) = $TYPO3_DB->sql_fetch_row($rs);
header ("Location: $url");
?>
