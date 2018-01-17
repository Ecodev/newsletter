<?php

namespace Ecodev\Newsletter\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Format array of values to CSV format
 */
class CsvValuesViewHelper extends AbstractViewHelper
{
    /**
     * Format array of values to CSV format
     *
     * @param array $values array of values to output in CSV format
     *
     * @return string
     */
    public function render(array $values)
    {
        return GeneralUtility::csvValues($values);
    }
}
