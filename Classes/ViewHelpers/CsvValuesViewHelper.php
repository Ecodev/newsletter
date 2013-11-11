<?php

/**
 * Format array of values to CSV format
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_ViewHelpers_CsvValuesViewHelper extends Tx_Newsletter_ViewHelpers_AbstractViewHelper
{

    /**
     * Format array of values to CSV format
     *
     * @param array $values array of values to output in CSV format
     * @return string
     */
    public function render(array $values)
    {

        return t3lib_div::csvValues($values);
    }

}
