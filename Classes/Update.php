<?php
namespace Ecodev\Newsletter;

use Ecodev\Newsletter\Update\Task;
use Ecodev\Newsletter\Update\TaskResult;
use Ecodev\Newsletter\Update\Transaction;
use Ecodev\Newsletter\Tools;

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
class Update extends AbstractUpdate
{

    /**
     * Register update tasks.
     */
    public static function registerUpdateTasks()
    {
        // Register the updates you want to perform here:
        
        // Notes on creating update tasks:
        // 1) Set the version of the extension when this update was added.
        // 2) Set whether the update tasks should be executed automatically (Task::AUTO_UPDATE) , manually via the Extension Manager(Task::MANUAL_UPDATE) or both (Task::UPDATE).
        // 3) Add a short human description of your update.[A translation key is also accepted here.]
        // 4) Set the callable \class::method path to execute the update.
        // 5) [OPTIONAL] Set any arguments you wish to pass to your method in an array.
        
        // Your Method MUST return a \Ecodev\Newsletter\Update\TaskResult on completion.
        self::registerUpdateTask(new Task('2.3.0', Task::UPDATE, 'Migrate old class paths in newsletter records.', '\Ecodev\Newsletter\Update::updateTaskMigrateClassPathsInRecords'));
        self::registerUpdateTask(new Task('2.5.2', Task::UPDATE, 'Encrypt plain-text bounce account passwords and configurations in newsletter records.', '\Ecodev\Newsletter\Update::updateTaskencryptOldBounceAccountPasswords'));
    }
    
    // Update Methods -- Optionally add your update methods below
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Notes: By using the Transaction:: methods you can get the data integrity state of your update operations.
    // Also if something goes wrong your operations may have been reversed, protecting the users original data.
    
    /**
     * Migrate old class paths in DB records
     *
     * @return \Ecodev\Newsletter\Update\TaskResult
     */
    public static function updateTaskMigrateClassPathsInRecords()
    {
        // Init the Task Result
        $taskResult = new TaskResult();
        
        // Set Queries
        $queries = array(
            "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:29:\"Tx_Newsletter_Task_SendEmails\"', 'O:33:\"Ecodev\\\\Newsletter\\\\Task\\\\SendEmails\"');",
            "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:31:\"Tx_Newsletter_Task_FetchBounces\"', 'O:35:\"Ecodev\\\\Newsletter\\\\Task\\\\FetchBounces\"');",
            "UPDATE tx_newsletter_domain_model_recipientlist SET type = REPLACE(type, 'Tx_Newsletter_Domain_Model_RecipientList_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\RecipientList\\\\');",
            "UPDATE tx_newsletter_domain_model_newsletter SET plain_converter = REPLACE(plain_converter, 'Tx_Newsletter_Domain_Model_PlainConverter_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\PlainConverter\\\\');"
        );
        
        // Execute Queries
        
        // As this is post- newsletter version 2.5.2 the newsletter MyISAM tables have been already converted to InnoDB.
        // So it is OK to do a InnoDB transaction here.
        
        /* @var $transactedResults \Ecodev\Newsletter\Update\TransactionResult */
        $transactedResults = Transaction::transactInnoDBQueries($queries);
        
        // Set the Task outcomes.
        $taskResult->success = $transactedResults->complete();
        $taskResult->numRecordsModified = $transactedResults->getAffectedDataCount();
        $taskResult->recordsCommitted = ! $transactedResults->getDataIntegrity();
        $taskResult->errorMessage = $transactedResults->getErrorMessage();
        
        return $taskResult;
    }

    /**
     * Encrypt old bounce account passwords
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @return \Ecodev\Newsletter\Update\TaskResult
     */
    public static function updateTaskEncryptOldBounceAccountPasswords()
    {
        // Init the Task Result
        $taskResult = new TaskResult();
        
        // Prepare Queries
        // Keep the old config to not break old installations
        $config = Tools::encrypt("poll ###SERVER###\nproto ###PROTOCOL### \nusername \"###USERNAME###\"\npassword \"###PASSWORD###\"\n");
        
        // Fetch and update the old records - they will have a default port and an empty config.
        global $TYPO3_DB;
        $rs = $TYPO3_DB->exec_SELECTquery('uid,password', 'tx_newsletter_domain_model_bounceaccount', 'port = 0 AND config = \'\'');
        while (($records[] = $TYPO3_DB->sql_fetch_assoc($rs)) || array_pop($records));
        $TYPO3_DB->sql_free_result($rs);
        
        // Set Queries
        $queries = array();
        if (! empty($records)) {
            foreach ($records as $row) {
                $queries[] = $TYPO3_DB->UPDATEquery('tx_newsletter_domain_model_bounceaccount', 'uid=' . intval($row['uid']), array(
                    'password' => Tools::encrypt($row['password']),
                    'config' => $config
                ));
            }
        }
        
        // Execute Queries
        /* @var $transactedResults \Ecodev\Newsletter\Update\TransactionResult */
        $transactedResults = Transaction::transactInnoDBQueries($queries);
        
        // Set the Task outcomes.
        $taskResult->success = $transactedResults->complete();
        $taskResult->numRecordsModified = $transactedResults->getAffectedDataCount();
        $taskResult->recordsCommitted = ! $transactedResults->getDataIntegrity();
        $taskResult->errorMessage = $transactedResults->getErrorMessage();
        
        return $taskResult;
    }
}