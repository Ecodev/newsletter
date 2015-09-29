<?php
namespace Ecodev\Newsletter\Update;

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
 * A model for update task results
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author marvin-martian https://github.com/marvin-martian
 */
class TaskResult
{

    /**
     * Whether the task was a success or not
     *
     * @var boolean
     */
    public $success = false;

    /**
     * A human result message.
     *
     * @var string
     */
    public $errorMessage = '';

    /**
     * The number of database records modified in the update.
     *
     * @var integer
     */
    public $numRecordsModified = 0;

    /**
     * State of whether database records were modified (added,deleted or updated) by the update.
     *
     * @var boolean
     */
    public $recordsCommitted = false;

    /**
     * The number of files modified (added,deleted or updated)in the update.
     *
     * @var integer
     */
    public $numFilesModified = 0;

    /**
     * State of whether files were modified (added,deleted or updated) by the update.
     *
     * @var boolean
     */
    public $filesCommitted = false;

    /**
     * The execution time (UNIX TIMSTAMP) of the task.
     *
     * @var integer
     */
    public $executionTime = -1;

    /**
     * Task results model constructor
     *
     * @param boolean $success
     * @param string $errorMessage
     * @param integer $numRecordsModified
     * @param boolean $recordsCommitted
     * @param integer $numFilesModified
     * @param boolean $filesCommitted
     */
    public function __construct($success = false, $errorMessage = '', $numRecordsModified = 0, $recordsCommitted = false, $numFilesModified = 0, $filesCommitted = false)
    {
        $this->success = $success;
        $this->errorMessage = $errorMessage;
        $this->numRecordsModified = $numRecordsModified;
        $this->recordsCommitted = $recordsCommitted;
        $this->numFilesModified = $numFilesModified;
        $this->filesCommitted = $filesCommitted;
    }
}
