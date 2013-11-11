<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Interface for Plain Text Converter. Converter may use either HTML
 * source or URL from where to fetch HTML source.
 */
interface Tx_Newsletter_Domain_Model_IPlainConverter
{

    /**
     * Set the data to be converted. Either $content or $contentUrl should be used
     * for the convertion. If one of them is useless just ignore it.
     * @param string $content HTML content to be converted to plain text
     * @param string $contentUrl URL of the content to be converted
     * @param string $baseUrl base URL which should be used for relative links
     */
    public function setContent($content, $contentUrl, $baseUrl);

    /**
     * Returns the plain text version of the content
     * @return string the converted content
     */
    public function getPlainText();
}
