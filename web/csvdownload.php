<?php
/**
 * This allows for secured download of CSV-data from targets
 */

require ('browserrun.php');
require_once(t3lib_extMgm::extPath('tcdirectmail').'class.tx_tcdirectmail_tools.php');

$target = tx_tcdirectmail_target::loadTarget(intval($_REQUEST['uid']));
if (t3lib_div::stdAuthCode($target->fields) == $_REQUEST['authCode']) {
   header('Content-type: text/comma-separated-values');
   header('Content-Disposition: attachment; filename="'.$target->fields['title'].'-'.$target->fields['uid'].'.csv"');
   
   while ($row = $target->getRecord()) {
      print (t3lib_div::csvValues($row)."\r\n");
   }
}

?>
