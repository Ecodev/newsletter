<?php

require_once(t3lib_extMgm::extPath('newsletter').'class.tx_tcdirectmail_target_array.php');
class tx_tcdirectmail_target_csvurl extends tx_tcdirectmail_target_array {
    function init() {
   $this->data = array();    
   if ($this->fields['csvurl'] && $this->fields['csvseparator'] && $this->fields['csvfields']) {
       $csvdata = t3lib_div::getURL($this->fields['csvurl']);
       $sepchar = $this->fields['csvseparator']?$this->fields['csvseparator']:',';
       $fields = array_map ('trim', explode ($sepchar, $this->fields['csvfields']));
       $lines = explode ("\n", $csvdata);
       foreach ($lines as $line) {
      $row = array();
      $values = explode($sepchar, $line);
      foreach ($values as $i => $value) {
          $row[$fields[$i]] = trim($value);
      }
      $this->data[] = $row;
       }
   }
    }
}

?>