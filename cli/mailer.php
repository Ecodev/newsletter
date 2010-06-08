#! /usr/bin/php -q
<?php
/*************************************************************** 
*  Copyright notice 
* 
*  (c) 2008 Daniel Schledermann <daniel@schledermann.net> 
*  All rights reserved 
* 
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify 
*  it under the terms of the GNU General Public License as published by 
*  the Free Software Foundation; either version 2 of the License, or 
*  (at your option) any later version. 
* 
*  The GNU General Public License can be found at 
*  http://www.gnu.org/copyleft/gpl.html. 
* 
*  This script is distributed in the hope that it will be useful, 
*  but WITHOUT ANY WARRANTY; without even the implied warranty of 
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
*  GNU General Public License for more details. 
* 
*  This copyright notice MUST APPEAR in all copies of the script! 
***************************************************************/
require('clirun.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_tcdirectmail_tools.php");

/***************** Send script ********************/
/* List pages NOT to send */

$rs = $TYPO3_DB->sql_query("SELECT pid FROM tx_tcdirectmail_lock  
                               WHERE stoptime = 0");
$pids[] = -1;

while (list($pid) = $TYPO3_DB->sql_fetch_row($rs)) {
   $pids[] = $pid;
}

$pids = implode(',',$pids);

/* Get a ready-to-send page */
$rs = $TYPO3_DB->sql_query("SELECT * 
                              FROM pages 
                              WHERE tx_tcdirectmail_senttime <= UNIX_TIMESTAMP() 
                              AND tx_tcdirectmail_senttime > 0 
                              AND doktype = 189 
                              AND uid NOT IN ($pids)
                              AND deleted = 0
                              AND hidden = 0
                              LIMIT 1");

if ($page = $TYPO3_DB->sql_fetch_assoc($rs)) {
    $begintime = time();
    /* Lock the page */
    $TYPO3_DB->exec_INSERTquery('tx_tcdirectmail_lock', 
                   array('pid' => $page['uid'], 
                         'begintime' => $begintime, 
                         'stoptime' => 0));
                         
    $lockid = $TYPO3_DB->sql_insert_id();

    tx_tcdirectmail_tools::createSpool($page, $begintime);

    /* Unlock the page */
    tx_tcdirectmail_tools::setScheduleAfterSending ($page);
    $TYPO3_DB->exec_UPDATEquery('tx_tcdirectmail_lock', "uid = $lockid", array('stoptime' => time()));
}


/* Get all test pages */
$rs = $TYPO3_DB->sql_query("SELECT * 
                              FROM pages 
                              WHERE tx_tcdirectmail_dotestsend = 1 
                              AND deleted = 0
                              AND hidden = 0           
                              AND doktype = 189");
			      
/* Each pages */    
while ($page = $TYPO3_DB->sql_fetch_assoc($rs)) {
    tx_tcdirectmail_tools::mailForTest($page);
    $TYPO3_DB->sql_query("UPDATE pages SET tx_tcdirectmail_dotestsend = 0 WHERE uid = $page[uid]");
}

tx_tcdirectmail_tools::runSpool();
                            
?>
