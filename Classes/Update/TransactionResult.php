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
 * A model for transaction states of operands
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TransactionResult
{
    /**
     * Whether the transaction was completed or not
     *
     * @var bool
     */
    protected $completed = false;

    /**
     * Error message associated with transaction.
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * The integrity state of whether or not data was modified.
     * A value TRUE means it was not modified.
     *
     * @var bool
     */
    protected $dataIntegrity = true;

    /**
     * The number of data instances affected by the transaction.
     *
     * @var int
     */
    protected $affectedDataCount = 0;

    /**
     * The total number of operands in the transaction.
     *
     * @var int
     */
    protected $totalOperands = 0;

    /**
     * The number of operands processed in the transaction.
     *
     * @var int
     */
    protected $processedOperands = 0;

    /**
     * Transaction state constructor.
     *
     * @param number $totalOperands
     */
    public function __construct($totalOperands = 0)
    {
        $this->totalOperands = $totalOperands;
        $this->completed = ($this->processedOperands >= $this->totalOperands);
        $this->dataIntegrity = ($this->affectedDataCount == 0);
    }

    /**
     * Steps the count of processed operands and updates the transaction complete state.
     */
    public function stepProcessed()
    {
        ++$this->processedOperands;
        $this->completed = ($this->processedOperands >= $this->totalOperands);
    }

    /**
     * Error message setter.
     *
     * @param string $message
     */
    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    /**
     * Error message getter
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * The complete state of the transaction.
     *
     * @return bool
     */
    public function complete()
    {
        return $this->completed;
    }

    /**
     * Affected data count getter.
     *
     * @return int
     */
    public function getAffectedDataCount()
    {
        return $this->affectedDataCount;
    }

    /**
     * Appends the count of affected data.
     *
     * @param int $amount
     */
    public function appendAffectedDataCount($amount)
    {
        $this->affectedDataCount += $amount;
        $this->dataIntegrity = ($this->affectedDataCount == 0);
    }

    /**
     * Resets the data integrity.
     */
    public function resetDataIntegrity()
    {
        $this->affectedDataCount = 0;
        $this->dataIntegrity = ($this->affectedDataCount == 0);
    }

    /**
     * Returns the integrity state of whether or not data was modified.
     * A value TRUE means it was not modified and the data remains integral.
     *
     * @return bool
     */
    public function getDataIntegrity()
    {
        return $this->dataIntegrity;
    }
}
