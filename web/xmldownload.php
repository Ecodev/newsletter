<?php
/**
 * This allows for secured download of CSV-data from targets
 */

require ('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
$recipientList = $recipientListRepository->findByUidInitialized(intval($_REQUEST['uid']));
		
if (t3lib_div::stdAuthCode($recipientList->_getCleanProperties()) == $_REQUEST['authCode']) {
	$title = $recipientList->getTitle();
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="' . $title . '-' . $recipientList->getUid() . '.xml"');
	
	print("<recipientList title=\"$title\">\r\n");
	 
	while ($row = $recipientList->getRecipient()) {
		print("\t<recipient>\r\n");
		 
		foreach ($row as $field => $value) {
			print("\t\t<$field>$value</$field>\r\n");
		}

		print("\t</recipient>\r\n");
	}
	 
	print("</recipientList>\r\n");
}

