<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * Recipient List using CSV list (values directly input in TYPO3 Backend)
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvList extends CsvFile
{
    /**
     * csvValues
     *
     * @var string $csvValues
     */
    protected $csvValues = '';

    /**
     * Setter for csvValues
     *
     * @param string $csvValues csvValues
     * @return void
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
