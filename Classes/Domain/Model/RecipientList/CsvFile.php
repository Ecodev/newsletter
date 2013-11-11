<?php

/**
 * Provides compatiblity with PHP 5.2.9 because 'str_getcsv' was only introduced in PHP 5.3.0
 */
if (!function_exists('str_getcsv')) {

    function str_getcsv($input, $delimiter = ',', $enclosure = '"', $notUsed = null)
    {
        $temp = fopen("php://memory", "rw");
        fwrite($temp, $input);
        fseek($temp, 0);
        $r = fgetcsv($temp, 0, $delimiter, $enclosure);
        fclose($temp);

        return $r;
    }

}

/**
 * Recipient List using CSV file
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_RecipientList_CsvFile extends Tx_Newsletter_Domain_Model_RecipientList_Array
{

    /**
     * csvSeparator
     *
     * @var string $csvSeparator
     */
    protected $csvSeparator;

    /**
     * csvFields
     *
     * @var string $csvFields
     */
    protected $csvFields;

    /**
     * csvFilename
     *
     * @var string $csvFilename
     */
    protected $csvFilename;

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

    function init()
    {
        $this->loadCsvFromFile(PATH_site . 'uploads/tx_newsletter/' . $this->getCsvFilename());
    }

    /**
     * Load data from a CSV file.
     * @param $filename path to the CSV file may be on disk or remote URL
     */
    protected function loadCsvFromFile($filename)
    {
        $csvdata = null;
        if ($filename) {
            $csvdata = t3lib_div::getURL($filename);
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
                if (!trim($line))
                    continue;

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

        if (isset($this->error))
            return $this->error;

        parent::getError();
    }

}
