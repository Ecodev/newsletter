<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
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
 * ************************************************************* */
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newsletter') . 'Classes/BounceHandler.php';

// If nothing is piped to this script, can't do anything
if (ftell(STDIN) === false) {
    throw new Exception('This script expects a raw email source to be piped from fetchmail');
}

// Read piped email raw source
$content = file_get_contents('php://stdin');

// Dispatch it to analyze its bounce level an take appropriate action
$bounceHandler = new \Ecodev\Newsletter\BounceHandler($content);
$bounceHandler->dispatch();
