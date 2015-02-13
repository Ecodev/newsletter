<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
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

/**
 * Class to migrate DB records
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ext_update
{
    /**
     * SQL queries to do migration
     * @var array
     */
    private $queries = array(
        "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:29:\"Tx_Newsletter_Task_SendEmails\"', 'O:33:\"Ecodev\\\\Newsletter\\\\Task\\\\SendEmails\"');",
        "UPDATE tx_newsletter_domain_model_recipientlist SET type = REPLACE(type, 'Tx_Newsletter_Domain_Model_RecipientList_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\RecipientList\\\\');",
        "UPDATE tx_newsletter_domain_model_newsletter SET plain_converter = REPLACE(plain_converter, 'Tx_Newsletter_Domain_Model_PlainConverter_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\PlainConverter\\\\');",
    );

    /**
     * Main function, returning the HTML content of the module
     * @return	string HTML to display
     */
    function main()
    {
        $content = '';
        $content .= '<h2>Migration from Newsletter 2.2.3 to 2.3.0</h2>';
        $content .= '<form name="migrateForm" action="" method ="post">';
        $content .= '<p>Records in database from Newsletter 2.2.3, or earlier, will be migrated to 2.3.0, or later. This action is idempotent and can be repeated if necessary.</p>';
        $content .= '<p><input type="submit" name="domigration" value ="Migrate" /></p>';
        $content .= '</form>';

        // Action! Makes the necessary update
        $update = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('domigration');

        // The update button was clicked, migrate stuff
        if (!empty($update)) {
            $recordCount = $this->doMigration();
            $content .= '<h3>Results</h3>';
            $content .= "<p>$recordCount records successfully migrated.</p>";
        }

        return $content;
    }

    /**
     * Apply the migration
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     */
    private function doMigration()
    {
        global $TYPO3_DB;

        $recordCount = 0;
        foreach ($this->queries as $query) {
            $res = $TYPO3_DB->sql_query($query);
            $error = $TYPO3_DB->sql_error();
            if ($error) {
                die("<pre>" . $query . "<br>" . $error . "</pre>");
            }
            $recordCount += $TYPO3_DB->sql_affected_rows($res);
        }

        return $recordCount;
    }

    /**
     * This method checks whether it is necessary to display the UPDATE option at all
     *
     * @param string $what What should be updated
     */
    function access($what = 'all')
    {
        return TRUE;
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newsletter/class.ext_update.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newsletter/class.ext_update.php']);
}

