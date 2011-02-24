<?php
/**
 * This allows for secured download of CSV-data from targets
 */
require ('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');

$target = Tx_Newsletter_Domain_Model_RecipientList::loadTarget(intval($_REQUEST['uid']));
if (t3lib_div::stdAuthCode($target->fields) == $_REQUEST['authCode']) {
   header('Content-type: text/xml');
   header('Content-Disposition: attachment; filename="'.$target->fields['title'].'-'.$target->fields['uid'].'.xml"');
   print ("<$target->tableName>\r\n");
   
   while ($row = $target->getRecord()) {
      print ("\t<record>\r\n");
   
      foreach ($row as $field => $value) {
         if ($value) {
            print ("\t\t<$field>$value</$field>\r\n");
         }
      }
      
      print ("\t</record>\r\n");
   }
   
   print ("</$target->tableName>\r\n");
}

?>
