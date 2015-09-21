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
 * A model for update tasks
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author marvin-martian https://github.com/marvin-martian
 */
class Task
{

    const AUTO_UPDATE = 'auto';

    const MANUAL_UPDATE = 'manual';

    const UPDATE = 'auto/manual';

    /**
     * Task Id
     *
     * @var string
     */
    protected $taskId;

    /**
     * The version of the extesnion when this update was added.
     *
     * @var string
     */
    protected $updateVersion;

    /**
     * Update mode of task
     *
     * @var string
     */
    protected $updateMode;

    /**
     * Current update mode of task
     *
     * @var string
     */
    protected $currentUpdateMode;

    /**
     * Task description
     *
     * @var string
     */
    protected $description;

    /**
     * Task update callable method
     *
     * @var string|array|false
     */
    protected $method;

    /**
     * Task arguments passed to method
     *
     * @var array
     */
    protected $methodArguments;

    /**
     * Task update status.
     *
     * @var boolean
     */
    protected $updated;

    /**
     * Last update time of task (UNIX Timestamp)
     *
     * @var integer
     */
    protected $lastUpdate;

    /**
     * Task execution result.
     *
     * @var \Ecodev\Newsletter\Update\TaskResult
     */
    protected $taskResult;

    /**
     * If the task is allowed to be executed.
     *
     * @var boolean
     */
    protected $executable;

    /**
     * If the task was executed.
     *
     * @var boolean
     */
    protected $executed;

    /**
     * Task model constructor
     *
     * @param string $updateVersion
     *            The extension version of when this update task was added.
     * @param string $updateMode
     *            The update mode of the task (Task::AUTO_UPDATE|Task::MANUAL_UPDATE|Task::UPDATE) [DEFAULT: Task::UPDATE]
     * @param string $description
     *            Short human readable description of the update task, can also be a translation key.
     * @param string $methodPath
     *            The callable method path of the update task.
     * @param array $methodArguments
     *            An array of arguments you wish to pass to the update method. [OPTIONAL]
     */
    public function __construct($updateVersion = '', $updateMode = 'auto/manual', $description = '', $methodPath = '', $methodArguments = array())
    {
        $tempArgs = func_get_args();
        unset($tempArgs[1]);
        $this->taskId = md5(serialize($tempArgs));
        $this->updateVersion = $updateVersion;
        $this->updateMode = $updateMode;
        $this->description = $description;
        $this->method = $this->getCallableMethod($methodPath);
        $this->methodArguments = $methodArguments;
        $this->executable = (boolean) $this->method;
        $this->executed = false;
        $this->updated = false;
        $this->lastUpdate = - 1;
    }

    /**
     * Returns the task id.
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Returns the task update mode.
     *
     * @return string
     */
    public function getTaskType()
    {
        return $this->updateMode;
    }

    /**
     * Returns the update version
     */
    public function getUpdateVersion()
    {
        return $this->updateVersion;
    }

    /**
     * Returns the task description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the update mode
     */
    public function getUpdateMode()
    {
        return $this->updateMode;
    }

    /**
     * Sets the current update mode.
     *
     * @param string $currentUpdateMode            
     */
    public function setCurrentUpdateMode($currentUpdateMode)
    {
        $this->currentUpdateMode = $currentUpdateMode;
    }

    /**
     * Returns the update method
     */
    public function getUpdateMethod()
    {
        return $this->method;
    }

    /**
     * Sets the update status of the task.
     *
     * @param array $updateHistory            
     */
    public function setStatus($updateHistory)
    {
        $this->updated = (boolean) isset($updateHistory[$this->taskId]);
        $this->lastUpdate = $this->updated ? $updateHistory[$this->taskId] : - 1;
    }

    /**
     * Retrieves the update status of the task.
     *
     * @return boolean
     */
    public function isUpdated()
    {
        return $this->updated;
    }

    /**
     * Sets/Gets the execution status of the task.
     *
     * @param boolean $execute            
     * @return boolean
     */
    public function canExecute($execute = NULL)
    {
        if (isset($execute) && ! is_null($execute)) {
            $this->executable = (boolean) $execute;
        }
        return $this->executable;
    }

    /**
     * Returns if the task was executed.
     *
     * @return boolean
     */
    public function wasExecuted()
    {
        return $this->executed;
    }

    /**
     * Gets the last updatetime of the task.
     *
     * @return integer UNIX Timestamp
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Executes the task method.
     *
     * @return boolean TRUE on success, FALSE on non-execution
     */
    public function execTask()
    {
        $this->executed = false;
        // Automatic tasks are only run once.
        if ($this->currentUpdateMode == self::AUTO_UPDATE && $this->updated) {
            return $this->executed;
        }
        
        if ($this->method) {
            $result = call_user_func_array($this->method, $this->methodArguments);
            if ($result instanceof \Ecodev\Newsletter\Update\TaskResult) {
                $result->executionTime = time();
                $this->executed = true;
                $this->taskResult = $result;
                $this->lastUpdate = $result->executionTime;
            }
        }
        return $this->executed;
    }

    /**
     * Returns the result of the task execution.
     *
     * @return \Ecodev\Newsletter\Update\TaskResult
     */
    public function getExecResult()
    {
        return $this->taskResult;
    }

    /**
     * Returns a callable method
     *
     * @param string $methodPath            
     * @return string|array|boolean Returns a callable method as string or array or FALSE on failure.
     */
    protected function getCallableMethod($methodPath)
    {
        if (version_compare(PHP_VERSION, '5.2.3') < 0) {
            $hasPaamayimNekudotayim = strpos($methodPath, '::');
            if (! ($hasPaamayimNekudotayim === false) && $hasPaamayimNekudotayim > 0) {
                $methodPath = explode($methodPath, '::');
            }
        }
        if (is_callable($methodPath)) {
            return $methodPath;
        }
        return false;
    }
}