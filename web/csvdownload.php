<?php
/**
 * This allows for secured download of CSV-data from targets
 */

require ('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$target = Tx_Newsletter_Domain_Model_RecipientList::loadTarget(intval($_REQUEST['uid']));
if (t3lib_div::stdAuthCode($target->fields) == $_REQUEST['authCode']) {
   header('Content-type: text/csv');
   header('Content-Disposition: attachment; filename="'.$target->fields['title'].'-'.$target->fields['uid'].'.csv"');
   
   while ($row = $target->getRecipient()) {
      print(t3lib_div::csvValues($row)."\r\n");
   }
}

