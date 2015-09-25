<?php
namespace Ecodev\Newsletter;

use Ecodev\Newsletter\Update\Task;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
require_once PATH_typo3 . 'sysext/core/Classes/SingletonInterface.php';

/**
 * Update for newsletter extension
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author marvin-martian https://github.com/marvin-martian
 */
abstract class AbstractUpdate implements \TYPO3\CMS\Core\SingletonInterface
{

    protected static $EXTKEY = 'newsletter';

    /**
     * Current extension version.
     *
     * @var $version string
     */
    protected static $version;

    /**
     * Extension Manager configuration of the extension
     *
     * @var $configManager \TYPO3\CMS\Core\Configuration\ConfigurationManager
     */
    protected static $configManager;

    /**
     * The local config of extension.
     *
     * @var $localConfig array
     */
    protected static $localConfig;

    /**
     * An array of updates to perform.
     *
     * @var $updates array
     */
    protected static $updateRegister;

    /**
     * An array of previous update tasks called.
     *
     * @var $updateHistory array
     */
    protected static $updateHistory;

    /**
     * Extension Manager configuration of the extension
     *
     * @var $updateHistory array
     */
    protected static $updateResults;

    // Public Methods
    // ////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Class constructor
     *
     * @param string $extkey
     */
    public function _construct($extkey = 'newsletter')
    {
        self::init($extkey);
    }

    /**
     * This is the public execution method that is triggered for manual updates.
     *
     * @return string HTML Content for manual updated.
     */
    public static function main()
    {
        $html = '';
        // Just a sanity check to make sure the extension is loaded.
        if (! ExtensionManagementUtility::isLoaded(self::$EXTKEY)) {
            return $html;
        }
        // Init the statics if necessary
        self::init(self::$EXTKEY);
        // If we have tasks to process.
        if (! empty(self::$updateRegister[Task::MANUAL_UPDATE])) {
            // Check on Form Submission.
            $formVars = GeneralUtility::_POST('tx_' . self::$EXTKEY);
            $message = '';
            $results = '';
            if ($formVars && ! empty($formVars['update'])) {
                if (! empty($formVars['update-tasks'])) {
                    // Set the seleceted tasks
                    $updateTasks = array_values($formVars['update-tasks']);
                    // Set the executable state of selected tasks.
                    /* @var $task \Ecodev\Newsletter\Update\Task */
                    foreach (self::$updateRegister[Task::MANUAL_UPDATE] as &$task) {
                        $task->canExecute(in_array($task->getTaskId(), $updateTasks));
                    }
                    // Start the manual updates.
                    self::doUpdates(Task::MANUAL_UPDATE);
                    // Set display HTML results;
                    if (! empty($formVars['update-tasks'])) {
                        $successState = '';
                        // Table header
                        $resultsTable = '<br /><table class="t3-table">';
                        $resultsTable .= '<thead>';
                        $resultsTable .= '<tr role="row">';
                        $resultsTable .= '<th></th>';
                        $resultsTable .= '<th>' . self::__('ext-update.task') . '</th>';
                        $resultsTable .= '<th>' . self::__('ext-update.since') . '</th>';
                        $resultsTable .= '<th style="white-space: nowrap;">' . self::__('ext-update.records_modified') . '</th>';
                        $resultsTable .= '<th style="white-space: nowrap;">' . self::__('ext-update.files_modified') . '</th>';
                        $resultsTable .= '<th>' . self::__('ext-update.problems') . '</th>';
                        $resultsTable .= '</tr>';
                        $resultsTable .= '</thead>';
                        $resultsTable .= '<tbody>';
                        // Step through results
                        foreach (self::$updateResults as &$task) {
                            // Set the success state.
                            if ($task->getExecResult()->success) {
                                $successState = $successState == '' ? FlashMessage::OK : $successState;
                            } else {
                                // Check if the failure was destructive or not.
                                if ($task->getExecResult()->recordsCommitted || $task->getExecResult()->filesCommitted) {
                                    $successState = $successState != FlashMessage::ERROR ? FlashMessage::ERROR : $successState;
                                } else {
                                    $successState = $successState != FlashMessage::ERROR ? FlashMessage::WARNING : $successState;
                                }
                            }
                            $taskIcon = $task->getExecResult()->success ? 'status-dialog-ok' : (($task->getExecResult()->recordsCommitted || $task->getExecResult()->filesCommitted) ? 'status-dialog-error' : 'status-dialog-warning');
                            $taskProblems = empty($task->getExecResult()->errorMessage) ? '<i>' . self::__('ext-update.none') . '</i>' : self::__(htmlspecialchars($task->getExecResult()->errorMessage));
                            // Render row.
                            $resultsTable .= '<tr role="row">';
                            $resultsTable .= '<td>' . IconUtility::getSpriteIcon($taskIcon) . '</td>';
                            $resultsTable .= '<td>' . self::__($task->getDescription()) . '</td>';
                            $resultsTable .= '<td>' . 'v' . $task->getUpdateVersion() . '</td>';
                            $resultsTable .= '<td style="text-align:center;">' . $task->getExecResult()->numRecordsModified . '</td>';
                            $resultsTable .= '<td style="text-align:center;">' . $task->getExecResult()->numFilesModified . '</td>';
                            $resultsTable .= '<td>' . $taskProblems . '</td>';
                            $resultsTable .= '</tr>';
                        }
                        $resultsTable .= '</tbody></table>';
                        // Set the success state messages.
                        $successStateMessage = '';
                        $successStateMessageHeader = '';
                        switch ($successState) {
                            case FlashMessage::OK:
                                $successStateMessage = self::__('ext-update.all_updates_completed_successfully');
                                $successStateMessageHeader = self::__('ext-update.update_success');
                                break;
                            case FlashMessage::WARNING:
                                $successStateMessage = self::__('ext-update.some_update_problems_but_data_integrity_kept') . ' ' . self::__('ext-update.please_review_update_results_below');
                                $successStateMessageHeader = self::__('ext-update.update_warning');
                                break;
                            case FlashMessage::ERROR:
                                $successStateMessage = self::__('ext-update.some_critical_update_problems') . ' ' . self::__('ext-update.please_review_update_results_below');
                                $successStateMessageHeader = self::__('ext-update.update_error');
                                break;
                        }
                        if ($successState !== '') {
                            // Set the Success State if we have one.
                            /* @var $flashMessage \TYPO3\CMS\Core\Messaging\FlashMessage */
                            $flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $successStateMessage, $successStateMessageHeader, $successState);
                            $message .= $flashMessage->render();
                        }

                        // Set the Update Results
                        /* @var $flashMessage \TYPO3\CMS\Core\Messaging\FlashMessage */
                        $flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $resultsTable, self::__('ext-update.update_results'), \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);
                        $message .= $flashMessage->render();
                    }
                } else {
                    /* @var $flashMessage \TYPO3\CMS\Core\Messaging\FlashMessage */
                    $flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', self::__('ext-update.no_tasks_selected'), self::__('ext-update.attention'), \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
                    $message .= $flashMessage->render();
                }
            }
            // Init the statics again, if the form was submitted the local config may have changed.
            self::init(self::$EXTKEY);
            // Create Form ID
            $idAttrValue = uniqid(self::$EXTKEY);
            // Some Inline JS for row clicking checkbox toggle
            $script = '';
            $script .= '<script type="text/javascript">';
            $script .= '/*<![CDATA[*/ ';
            $script .= 'jQuery.noConflict();';
            $script .= 'jQuery( document ).ready(function() {';
            $script .= 'jQuery(\'#' . $idAttrValue . ' table tbody tr\').filter(\':has(:checkbox:checked)\').addClass(\'selected\').end().click(function(event) {';
            $script .= 'jQuery(this).toggleClass(\'selected\');if (event.target.type !== \'checkbox\') {';
            $script .= 'jQuery(\':checkbox\', this).each(function() {jQuery(this).prop(\'checked\',!this.checked); });';
            $script .= '}';
            $script .= '});';
            $script .= '});';
            $script .= ' /*]]>*/';
            $script .= '</script>';
            // Build the update form
            $form = '';
            $form .= '<form id="' . $idAttrValue . '" name="tx_' . self::$EXTKEY . '[updateForm]" action ="" method="post">';
            $form .= '<fieldset>';
            $form .= '<legend><h4>&nbsp;' . self::__('ext-update.manual_updates') . '&nbsp;</h4></legend>';
            $form .= '<p>' . self::__('ext-update.select_manual_tasks') . '<br />' . self::__('ext-update.new_install_advice') . '</p>';
            $form .= '<p>' . self::__('ext-update.current_version', array(
                '<u>v' . self::$version . '</u>',
            )) . '</p>';
            $form .= '<h4>' . self::__('ext-update.update_tasks') . '</h4>';
            $form .= '<table class="t3-table">';
            $form .= '<thead>';
            $form .= '<tr role="row">';
            $form .= '<th></th>';
            $form .= '<th>' . self::__('ext-update.task') . '</th>';
            $form .= '<th>' . self::__('ext-update.since') . '</th>';
            $form .= '<th>' . self::__('ext-update.update_type') . '</th>';
            $form .= '<th>' . self::__('ext-update.last_updated') . '</th>';
            $form .= '</tr>';
            $form .= '</thead>';
            $form .= '<tbody>';
            /* @var $task \Ecodev\Newsletter\Update\Task */
            foreach (self::$updateRegister[Task::MANUAL_UPDATE] as &$task) {
                // set some attributes
                $idAttrValue = uniqid(self::$EXTKEY);
                $checked = $task->isUpdated() ? '' : ' checked="checked"';
                // set some pretty dates.
                $age = $task->getLastUpdate() > - 1 ? (time() - $task->getLastUpdate()) : - 1;
                $lastUpdate = $task->getLastUpdate() > - 1 ? ($age < (3600 * 3) ? self::__('ext-update.ago', array(
                    BackendUtility::calcAge($age, $GLOBALS["LANG"]->sL("LLL:EXT:lang/locallang_core.xlf:labels.minutesHoursDaysYears")),
                )) : strftime('%c', $task->getLastUpdate())) : '<i>' . self::__('ext-update.never') . '</i>';
                // render row
                $form .= '<tr role="row" >';
                $form .= '<td><input type="checkbox" id="' . $idAttrValue . '" name="tx_' . self::$EXTKEY . '[update-tasks][' . $task->getTaskId() . ']" value="' . $task->getTaskId() . '"' . $checked . '></td>';
                $form .= '<td>' . self::__($task->getDescription()) . '</td>';
                $form .= '<td>' . 'v' . $task->getUpdateVersion() . '</td>';
                $form .= '<td>' . $task->getTaskType() . '</td>';
                $form .= '<td>' . $lastUpdate . '</td>';
                $form .= '</tr>';
            }
            $form .= '</tbody>';
            $form .= '</table>';
            $idAttrValue = uniqid(self::$EXTKEY);
            $form .= '<br /><input id="' . $idAttrValue . '" type="submit" name="tx_' . self::$EXTKEY . '[update]" value="' . self::__('ext-update.update_selected') . '" />';
            $form .= '</fieldset>';
            $form .= '</form>';
            // put it all together.
            $html = $message . ' <br />' . $form . $script;
        }

        return $html;
    }

    /**
     * This method checks whether it is necessary to display the UPDATE option at all for manual updates.
     *
     * @return boolean if user have access, otherwise false
     */
    public static function access()
    {
        // No point in offering manual updates if the extension is not installed.
        if (! ExtensionManagementUtility::isLoaded(self::$EXTKEY)) {
            return false;
        }
        // Init the statics if necessary
        self::init(self::$EXTKEY);
        // Check if we have any manual tasks to execute.
        return ! empty(self::$updateRegister[Task::MANUAL_UPDATE]);
    }

    /**
     * This is the public execution method that is triggered by the signal/slot for automatic updates.
     *
     * @param string $extname
     */
    public static function autorun($extname = null)
    {
        // Only concerned on running auto-updates if it is the newsletter extension that was installed and IS installed.
        if ($extname != self::$EXTKEY && ! ExtensionManagementUtility::isLoaded(self::$EXTKEY)) {
            return;
        }
        // Init the statics if necessary
        self::init(self::$EXTKEY);
        // Start the auto updates.
        self::doUpdates(Task::AUTO_UPDATE);
    }

    /**
     * Register update tasks.
     */
    public static function registerUpdateTasks()
    {
    }

    /**
     * Registers an update task to perform.
     *
     * @param \Ecodev\Newsletter\Update\Task $task
     * @return boolean
     */
    public static function registerUpdateTask($task)
    {
        if (! ($task instanceof \Ecodev\Newsletter\Update\Task)) {
            return false;
        }
        // Init the statics if necessary
        self::init(self::$EXTKEY);
        // Set update status of task.
        $task->setStatus(self::$updateHistory);
        switch ($task->getUpdateMode()) {
            case Task::AUTO_UPDATE:
            case Task::MANUAL_UPDATE:
                self::setUpdateRegister($task->getUpdateMode(), $task);
                break;
            default:
                // add them to both lists.
                self::setUpdateRegister(Task::AUTO_UPDATE, $task);
                self::setUpdateRegister(Task::MANUAL_UPDATE, $task);
        }

        return true;
    }

    // Protected Methods
    // ////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Initialize some variables.
     */
    protected static function init($extkey)
    {
        // Init the static vars if need be.
        self::$EXTKEY = $extkey;
        // Get the current extension version.
        if (! isset(self::$version)) {
            self::$version = ExtensionManagementUtility::getExtensionVersion(self::$EXTKEY);
        }
        // Get a configuration manager
        if (! isset(self::$configManager) || ! (self::$configManager instanceof TYPO3\CMS\Core\Configuration\ConfigurationManager)) {
            self::$configManager = GeneralUtility::makeInstance('TYPO3\CMS\Core\Configuration\ConfigurationManager');
        }
        // Get/Update local configuration for extension.
        self::$localConfig = unserialize(self::$configManager->getLocalConfigurationValueByPath('EXT/extConf/' . self::$EXTKEY));
        // Get what updates have already been performed.
        if (! isset(self::$updateHistory)) {
            self::$updateHistory = array();
        }
        // Set update history
        if (isset(self::$localConfig['updateHistory'])) {
            self::$updateHistory = self::$localConfig['updateHistory'];
        }
        // Init the update register
        if (! isset(self::$updateRegister)) {
            self::$updateRegister = array(
                Task::AUTO_UPDATE => array(),
                Task::MANUAL_UPDATE => array(),
            );
            // Register the update tasks.
            static::registerUpdateTasks();
        }
    }

    /**
     * Update the local configuration
     */
    protected static function updateLocalConfig()
    {
        // Update the localConfig
        self::$localConfig['updateHistory'] = self::$updateHistory;
        $localConfig = array(
            'EXT' => array(
                'extConf' => array(
                    self::$EXTKEY => serialize(self::$localConfig),
                ),
            ),
        );
        // Write the changes to file.
        self::$configManager->updateLocalConfiguration($localConfig);
    }

    /**
     * Starts a number of update tasks to perform.
     */
    protected static function doUpdates($updateMode)
    {
        self::$updateResults = array();
        if (isset(self::$updateRegister) && self::$updateRegister[$updateMode]) {
            // Execute the tasks.
            /* @var $task \Ecodev\Newsletter\Update\Task */
            foreach (self::$updateRegister[$updateMode] as &$task) {
                if (($task instanceof Task)) {
                    $task->setCurrentUpdateMode($updateMode);
                    if ($task->canExecute() && $task->execTask()) {
                        self::$updateResults[] = $task;
                    }
                }
            }
            // Process the results
            self::processUpdateResults($updateMode);
            // Update local config
            self::updateLocalConfig();
        }
    }

    /**
     * Sets the update registry with a value pair.
     *
     * @param string $updateMode
     * @param \Ecodev\Newsletter\Update\Task $task
     */
    protected static function setUpdateRegister($updateMode, $task)
    {
        self::$updateRegister[$updateMode][$task->getTaskId()] = $task;
        // Sort it by task version
        uasort(self::$updateRegister[$updateMode], function ($a, $b) {
            /* @var $a \Ecodev\Newsletter\Update\Task */
            /* @var $b \Ecodev\Newsletter\Update\Task */
            return - 1 * version_compare($a->getUpdateVersion(), $b->getUpdateVersion());
        });
    }

    /**
     * Checks and records the state of the update.
     */
    protected static function processUpdateResults($updateMode)
    {
        if (! empty(self::$updateResults)) {
            $warnings = array();
            $successes = array();
            /* @var $task \Ecodev\Newsletter\Update\Task */
            foreach (self::$updateResults as &$task) {
                if ($task->wasExecuted()) {
                    $execResult = &$task->getExecResult();
                    if ($execResult->success) {
                        $successes[] = self::__('"(v%s) %s"', array(
                            $task->getUpdateVersion(),
                            $task->getDescription(),
                        ));
                        // Add to update history
                        self::$updateHistory[$task->getTaskId()] = $execResult->executionTime;
                    } else {
                        // If for some strange reason it was marked as successful remove it from history.
                        if (isset(self::$updateHistory[$task->getTaskId()])) {
                            unset(self::$updateHistory[$task->getTaskId()]);
                        }
                        $warnings[] = self::__('"(v%s) %s - Record Integrity: %s - File Integrity: %s - ErrorMessage: %s"', array(
                            $task->getUpdateVersion(),
                            $task->getDescription(),
                            (($execResult->recordsCommitted && $execResult->numRecordsModified > 0) ? 'Modified ' . $execResult->numRecordsModified . ' Records' : 'OK'),
                            (($execResult->filesCommitted && $execResult->numFilesModified > 0) ? 'Modified ' . $execResult->numFilesModified . ' Files' : 'OK'),
                            self::__($execResult->errorMessage),
                        ));
                    }
                }
            }
            // Log any information.
            if ($successes) {
                self::log('info', $updateMode, self::__('Completed %s update task/s successfully. [ %s ]', array(
                    count($successes),
                    implode(', ', $successes),
                )));
            }
            if ($warnings) {
                self::log('warn', $updateMode, self::__('Encountered %s problem update task/s. [ %s ]', array(
                    count($warnings),
                    implode(', ', $warnings),
                )));
            }

            return;
        }
        self::log('info', $updateMode, self::__('Nothing to update.'));
    }

    /**
     * Log an update event for the extension.
     *
     * @param string $logMethod
     * @param string $updateMode
     * @param string $message
     */
    protected static function log($logMethod, $updateMode, $message)
    {
        /* @var $logger \TYPO3\CMS\Core\Log\Logger */
        static $logger;
        // Get a logger
        if (! ($logger instanceof \TYPO3\CMS\Core\Log\Logger)) {
            $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        }
        if (method_exists($logger, $logMethod)) {
            $logger->$logMethod(self::__('EXT: %s (v%s) %s-Update: ' . $message, array(
                self::$EXTKEY,
                self::$version,
                ucfirst($updateMode),
            )));
        }
    }

    /**
     * Translates extension text or returns the extension key or text.
     *
     * @param string $translation_key
     * @param array $substitutions
     * @return string
     */
    protected static function __($translation_key, $substitutions = array())
    {
        if ($translation_key !== null) {
            // Check locallang_update.xlf translations first.
            $llPath = 'LLL:EXT:' . self::$EXTKEY . '/Resources/Private/Language/locallang_update.xlf:';
            $text = LocalizationUtility::translate($llPath . $translation_key, self::$EXTKEY, $substitutions);
            // Try elsewhere (locallang.xlf)
            if ($text == null || $text == '') {
                $text = LocalizationUtility::translate($translation_key, self::$EXTKEY, $substitutions);
            }

            return ($text == null || $text == '') ? vsprintf($translation_key, $substitutions) : $text;
        }

        return '';
    }
}
