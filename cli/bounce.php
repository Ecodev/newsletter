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
require_once('clirun.php');

/* Write a new fetchmailrc */
$fetchmailhome = PATH_site.'uploads/tx_newsletter';
$fetchmailfile = "$fetchmailhome/fetchmailrc";
$servers = array();
$fd = fopen($fetchmailfile, 'w');
$rs = $TYPO3_DB->sql_query("SELECT servertype, server, username, passwd FROM tx_newsletter_domain_model_bounceaccount
                                WHERE hidden = 0 
                                AND deleted = 0");
                                     
while (list($type, $server, $username, $passwd) = $TYPO3_DB->sql_fetch_row($rs)) {
   $contents .= "poll $server proto $type username \"$username\" password \"$passwd\"\n";
   $servers[] = $server;
}

fwrite($fd, $contents);
fclose($fd);
chmod($fetchmailfile, 0600); 

putenv("FETCHMAILHOME=$fetchmailhome");

$theconf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['newsletter']);
$fetchmail = $theconf['path_to_fetchmail'];

/* Keep messages on server */
if ($theconf['keep_messages']) {
    $keep = '--keep ';
}


foreach ($servers as $server) {
   exec($fetchmail.' -m '.dirname(__FILE__).'/readmail.php -s '.$keep.$server, $result);
}

unlink($fetchmailfile);
?>
