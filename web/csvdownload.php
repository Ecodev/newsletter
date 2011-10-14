<?php
/**
 * This allows for secured download of CSV-data from targets
 */

require ('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
$recipientList = $recipientListRepository->findByUidInitialized(intval($_REQUEST['uid']));

if (t3lib_div::stdAuthCode($recipientList->_getCleanProperties()) == $_REQUEST['authCode']) {
   header('Content-type: text/csv');
   header('Content-Disposition: attachment; filename="' . $recipientList->getTitle() . '-' . $recipientList->getUid() . '.csv"');
   
   while ($row = $recipientList->getRecipient()) {
      print(t3lib_div::csvValues($row)."\r\n");
   }
}

