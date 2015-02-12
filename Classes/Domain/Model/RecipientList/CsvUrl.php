<?php


namespace Ecodev\Newsletter\Domain\Model\RecipientList;

use Ecodev\Newsletter\Domain\Model\RecipientList\CsvFile;



/**
 * Recipient List using CSV url to retrieve a CSV file
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvUrl extends CsvFile
{

    /**
     * csvUrl
     *
     * @var string $csvUrl
     */
    protected $csvUrl;

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

    function init()
    {
        $this->loadCsvFromFile($this->getCsvUrl());
    }

}
