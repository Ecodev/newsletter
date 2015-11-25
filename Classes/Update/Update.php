<?php

namespace Ecodev\Newsletter\Update;

use Ecodev\Newsletter\Tools;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/*
 * *************************************************************
 * Copyright notice
 *
 * (c) 2015
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************
 */

/**
 * Update for extensions
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author marvin-martian https://github.com/marvin-martian
 */
class Update implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Execute all necessary updates
     *
     * @param string $extensionName
     */
    public static function update($extensionName)
    {
        // Only concerned on running auto-updates if it is the newsletter extension that was installed and IS installed.
        if ($extensionName != 'newsletter' && ! ExtensionManagementUtility::isLoaded($extensionName)) {
            return;
        }

        $queries = array_merge(self::getQueriesToMigrateClassPathsInRecords(), self::getQueriesToEncryptOldBounceAccountPasswords());

        /* @var $transactedResults \Ecodev\Newsletter\Update\TransactionResult */
        $transactedResults = Transaction::transactInnoDBQueries($queries);

        // Set the Task outcomes.
        if (!$transactedResults->complete()) {
            throw new \Exception($transactedResults->getErrorMessage(), 1448435734);
        }
    }

    /**
     * Return queries to migrate old class paths in newsletter records
     *
     * @return string[]
     */
    private static function getQueriesToMigrateClassPathsInRecords()
    {
        return array(
            "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:29:\"Tx_Newsletter_Task_SendEmails\"', 'O:33:\"Ecodev\\\\Newsletter\\\\Task\\\\SendEmails\"');",
            "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:31:\"Tx_Newsletter_Task_FetchBounces\"', 'O:35:\"Ecodev\\\\Newsletter\\\\Task\\\\FetchBounces\"');",
            "UPDATE tx_newsletter_domain_model_recipientlist SET type = REPLACE(type, 'Tx_Newsletter_Domain_Model_RecipientList_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\RecipientList\\\\');",
            "UPDATE tx_newsletter_domain_model_newsletter SET plain_converter = REPLACE(plain_converter, 'Tx_Newsletter_Domain_Model_PlainConverter_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\PlainConverter\\\\');",
        );
    }

    /**
     * Encrypt old bounce account passwords
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @return string[]
     */
    private static function getQueriesToEncryptOldBounceAccountPasswords()
    {
        // Prepare Queries
        // Keep the old config to not break old installations
        $config = Tools::encrypt("poll ###SERVER###\nproto ###PROTOCOL### \nusername \"###USERNAME###\"\npassword \"###PASSWORD###\"\n");

        // Fetch and update the old records - they will have a default port and an empty config.
        global $TYPO3_DB;
        $rs = $TYPO3_DB->exec_SELECTquery('uid, password', 'tx_newsletter_domain_model_bounceaccount', 'port = 0 AND config = \'\'');
        while (($records[] = $TYPO3_DB->sql_fetch_assoc($rs)) || array_pop($records));
        $TYPO3_DB->sql_free_result($rs);

        // Set Queries
        $queries = array();
        if (!empty($records)) {
            foreach ($records as $row) {
                $queries[] = $TYPO3_DB->UPDATEquery('tx_newsletter_domain_model_bounceaccount', 'uid=' . intval($row['uid']), array(
                    'password' => Tools::encrypt($row['password']),
                    'config' => $config,
                ));
            }
        }

        return $queries;
    }
}
