<?php


namespace Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * Recipient List using CSV url to retrieve a CSV file
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvUrl extends CsvFile
{

    /**
     * csvUrl
     *
     * @var string $csvUrl
     */
    protected $csvUrl = '';

    /**
     * Setter for csvUrl
     *
     * @param string $csvUrl csvUrl
     * @return void
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
