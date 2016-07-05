<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * Recipient List using CSV list (values directly input in TYPO3 Backend)
 */
class CsvList extends CsvFile
{
    /**
     * csvValues
     *
     * @var string
     */
    protected $csvValues = '';

    /**
     * Setter for csvValues
     *
     * @param string $csvValues csvValues
     */
    public function setCsvValues($csvValues)
    {
        $this->csvValues = $csvValues;
    }

    /**
     * Getter for csvValues
     *
     * @return string csvValues
     */
    public function getCsvValues()
    {
        return $this->csvValues;
    }

    public function init()
    {
        $this->loadCsvFromData($this->getCsvValues());
    }
}
