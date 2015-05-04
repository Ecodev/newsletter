<?php


namespace Ecodev\Newsletter\ViewHelpers;

/**
 * Format array of values to CSV format
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvValuesViewHelper extends AbstractViewHelper
{

    /**
     * Format array of values to CSV format
     *
     * @param array $values array of values to output in CSV format
     * @return string
     */
    public function render(array $values)
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::csvValues($values);
    }
}
