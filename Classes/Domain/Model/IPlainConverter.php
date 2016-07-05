<?php

namespace Ecodev\Newsletter\Domain\Model;

/**
 * Interface for Plain Text Converter. Converter may use either HTML
 * source or URL from where to fetch HTML source.
 */
interface IPlainConverter
{
    /**
     * Returns the plain text version of the content
     * @param string $content HTML content to be converted to plain text
     * @param string $baseUrl base URL which should be used for relative links
     * @return string the converted content
     */
    public function getPlainText($content, $baseUrl);
}
