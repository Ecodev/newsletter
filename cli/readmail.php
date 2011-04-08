#! /usr/bin/php -q
<?php
require_once('clirun.php');
require_once(t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_bouncehandler.php');

$fd = fopen('php://stdin', 'r');
while ($buffer = fread($fd, 8096)) {
   $content .= $buffer;
}
fclose($fd);

$bounceHandler = new tx_newsletter_bouncehandler($content);
$bounceHandler->dispatch();
