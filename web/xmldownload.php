<?php
/**
 * This allows for secured download of CSV-data from targets
 */

require ('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$target = Tx_Newsletter_Domain_Model_RecipientList::loadTarget(intval($_REQUEST['uid']));
if (t3lib_div::stdAuthCode($target->fields) == $_REQUEST['authCode']) {
	$title = $target->fields['title'];
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="'.$title.'-'.$target->fields['uid'].'.xml"');
	
	print("<recipientList title=\"$title\">\r\n");
	 
	while ($row = $target->getRecord()) {
		print("\t<recipient>\r\n");
		 
		foreach ($row as $field => $value) {
			print("\t\t<$field>$value</$field>\r\n");
		}

		print("\t</recipient>\r\n");
	}
	 
	print("</recipientList>\r\n");
}

