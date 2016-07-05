<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * Recipient List using CSV url to retrieve a CSV file
 */
class CsvUrl extends CsvFile
{
    /**
     * csvUrl
     *
     * @var string
     */
    protected $csvUrl = '';

    /**
     * Setter for csvUrl
     *
     * @param string $csvUrl csvUrl
     */
    public function setCsvUrl($csvUrl)
    {
        $this->csvUrl = $csvUrl;
    }

    /**
     * Getter for csvUrl
     *
     * @return string csvUrl
     */
    public function getCsvUrl()
    {
        return $this->csvUrl;
    }

    public function init()
    {
        $this->loadCsvFromFile($this->getCsvUrl());
    }
}
