<?php

namespace Ecodev\Newsletter\Utility;

use Ecodev\Newsletter\Domain\Model\Email;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
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
 * Used to substitute markers in any kind of text
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MarkerSubstitutor
{
    private $simpleMarkersFound;
    private $advancedMarkersFound;

    /**
     * Substitude multiple markers to an URL
     * @param string $url
     * @param Email $email
     * @return string url with marker replaced
     */
    public function substituteMarkersInUrl($url, Email $email)
    {
        $prefix = '<a href="';
        $suffix = '">';
        $link = $prefix . $url . $suffix;

        $result = $this->substituteMarkers($link, $email, 'link');

        return substr($result, strlen($prefix), strlen($result) - strlen($prefix) - strlen($suffix));
    }

    /**
     * Apply multiple markers to mail contents
     * @param string $src
     * @param Email $email
     * @param string $name optionnal name to be forwarded to hook
     * @return string url with marker replaced
     */
    public function substituteMarkers($src, Email $email, $name = '')
    {
        $markers = $this->getMarkers($email);
        $result = $src;

        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['substituteMarkersHook'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['substituteMarkersHook'] as $_classRef) {
                $_procObj = \TYPO3\CMS\Core\Utility\GeneralUtility::getUserObj($_classRef);
                $result = $_procObj->substituteMarkersHook($result, $name, $markers);
            }
        }

        // For each marker, only substitute if the field is registered as a marker.
        // This approach has shown to speed up things quite a bit.
        $this->findExistingMarkers($src);
        foreach ($markers as $name => $value) {
            if (in_array($name, $this->advancedMarkersFound)) {
                $result = $this->substituteAdvancedMarker($result, $name, $value);
            }

            if (in_array($name, $this->simpleMarkersFound)) {
                $result = $this->substituteSimpleMarker($result, $name, $value);
            }
        }

        return $result;
    }

    /**
     * Find any markers that exists in the source
     * @param string $src
     */
    private function findExistingMarkers($src)
    {
        // Detect what markers we need to substitute later on
        preg_match_all('/###(\w+)###/', $src, $fields);
        preg_match_all('|"https?://(\w+)"|', $src, $fieldsLinks);
        $this->simpleMarkersFound = array_merge($fields[1], $fieldsLinks[1]);

        // Any advanced IF fields we need to sustitute later on
        $this->advancedMarkersFound = [];
        preg_match_all('/###:IF: (\w+) ###/U', $src, $fields);
        foreach ($fields[1] as $field) {
            $this->advancedMarkersFound[] = $field;
        }
    }

    /**
     * Return all markers and their values as associative array
     * @param Email $email
     * @return string[]
     */
    private function getMarkers(Email $email)
    {
        $markers = $email->getRecipientData();

        // Add predefined markers
        $authCode = $email->getAuthCode();
        $markers['newsletter_view_url'] = UriBuilder::buildFrontendUri($email->getPid(), 'Email', 'show', ['c' => $authCode]);
        $markers['newsletter_unsubscribe_url'] = UriBuilder::buildFrontendUri($email->getPid(), 'Email', 'unsubscribe', ['c' => $authCode]);

        return $markers;
    }

    /**
     * Replace a named marker with a supplied value
     * A simple marker can have the form of: ###marker###, http://marker, or https://marker
     * @param string $src Source to apply marker substitution to
     * @param string $name Name of the marker to replace
     * @param string $value Value to replace marker with
     * @return string Source with applied marker
     */
    private function substituteSimpleMarker($src, $name, $value)
    {     // All variants of the marker to search
        $search = [
            "###$name###",
            "http://$name",
            "https://$name",
            urlencode("###$name###"), // If the marker is in a link and the "links spy" option is activated it will be urlencoded
            urlencode("http://$name"),
            urlencode("https://$name"),
        ];

        $replace = [
            $value,
            $value,
            preg_replace('-^http://-', 'https://', $value),
            urlencode($value), // We need to replace with urlencoded value
            urlencode($value),
            urlencode(preg_replace('-^http://-', 'https://', $value)),
        ];

        return str_ireplace($search, $replace, $src);
    }

    /**
     * Substitute an advanced marker
     * An advanced conditionnal marker ###:IF: marker ### ..content.. (###:ELSE:###)? ..content.. ###:ENDIF:###
     * @param string $src Source to apply marker substitution to
     * @param string $name Name of the marker to replace
     * @param string $value Value to replace marker with
     * @return string Source with applied marker
     */
    private function substituteAdvancedMarker($src, $name, $value)
    {
        $tokenBegin = "###:IF: $name ###";
        $tokenElse = '###:ELSE:###';
        $tokenEnd = '###:ENDIF:###';
        while (($beginning = strpos($src, $tokenBegin)) !== false) {
            $end = strpos($src, $tokenEnd, $beginning);

            // If marker is not correctly terminated, cancel everything
            if ($end === false) {
                break;
            }

            // Find ELSE token but only before the ENDIF token
            $else = strpos($src, $tokenElse, $beginning);
            if ($else > $end) {
                $else = false;
            }

            // Find the text which will replace the marker
            if ($value) {
                $textBeginning = $beginning + strlen($tokenBegin);
                if ($else === false) {
                    $text = substr($src, $textBeginning, $end - $textBeginning);
                } else {
                    $text = substr($src, $textBeginning, $else - $textBeginning);
                }
            } else {
                if ($else === false) {
                    $text = '';
                } else {
                    $textBeginning = $else + strlen($tokenElse);
                    $text = substr($src, $textBeginning, $end - $textBeginning);
                }
            }

            // Do the actual replacement in the entire src (possibly replacing the same marker several times)
            $entireMarker = substr($src, $beginning, $end - $beginning + strlen(($tokenEnd)));
            $src = str_replace($entireMarker, $text, $src);
        }

        return $src;
    }
}
