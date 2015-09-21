<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * Recipient List using CSV file
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvFile extends AbstractArray
{
    /**
     * csvSeparator
     *
     * @var string $csvSeparator
     */
    protected $csvSeparator = ',';

    /**
     * csvFields
     *
     * @var string $csvFields
     */
    protected $csvFields = '';

    /**
     * csvFilename
     *
     * @var string $csvFilename
     */
    protected $csvFilename = '';

    /**
     * Setter for csvSeparator
     *
     * @param string $csvSeparator csvSeparator
     * @return void
     */
    public function setCsvSeparator($csvSeparator)
    {
        $this->csvSeparator = $csvSeparator;
    }

    /**
     * Getter for csvSeparator
     *
     * @return string csvSeparator
     */
    public function getCsvSeparator()
    {
        return $this->csvSeparator;
    }

    /**
     * Setter for csvFields
     *
     * @param string $csvFields csvFields
     * @return void
     */
    public function setCsvFields($csvFields)
    {
        $this->csvFields = $csvFields;
    }

    /**
     * Getter for csvFields
     *
     * @return string csvFields
     */
    public function getCsvFields()
    {
        return $this->csvFields;
    }

    /**
     * Setter for csvFilename
     *
     * @param string $csvFilename csvFilename
     * @return void
     */
    public function setCsvFilename($csvFilename)
    {
        $this->csvFilename = $csvFilename;
    }

    /**
     * Getter for csvFilename
     *
     * @return string csvFilename
     */
    public function getCsvFilename()
    {
        return $this->csvFilename;
    }

    /**
     * Return the path where CSV files are contained
     * @return string
     */
    protected function getPathname()
    {
        return PATH_site . 'uploads/tx_newsletter';
    }

    public function init()
    {
        $this->loadCsvFromFile($this->getPathname() . '/' . $this->getCsvFilename());
    }

    /**
     * Load data from a CSV file.
     * @param $filename path to the CSV file may be on disk or remote URL
     */
    protected function loadCsvFromFile($filename)
    {
        $csvdata = null;
        if ($filename) {
            $csvdata = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($filename);
        }

        $this->loadCsvFromData($csvdata);
    }

    /**
     * Load data from a CSV data.
     * @param $csvdata CSV data
     */
    protected function loadCsvFromData($csvdata)
    {
        $this->data = array();

        $sepchar = $this->getCsvSeparator() ? $this->getCsvSeparator() : ',';
        $keys = array_unique(array_map('trim', explode($sepchar, $this->getCsvFields())));

        if ($csvdata && $sepchar && count($keys)) {
            $lines = explode("\n", $csvdata);
            foreach ($lines as $line) {
                if (!trim($line)) {
                    continue;
                }

                $values = str_getcsv($line, $sepchar);
                if (count($values) != count($keys)) {
                    $this->error = sprintf('Field names count (%1$d) is not equal to values count (%2$d)', count($keys), count($values));
                }
                $row = array_combine($keys, $values);

                if ($row) {
                    $this->data[] = $row;
                }
            }
        }
    }

    public function getError()
    {
        if (isset($this->error)) {
            return $this->error;
        }

        parent::getError();
    }
}
