<?php

require_once (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_plain.php');

class tx_newsletter_plain_template extends tx_newsletter_plain {
    var $fetchMethod = 'url';
    
    function setHtml($url) {
       $this->plainText = tx_newsletter_tools::getURL("$url&type=99");
    }
}


?>