<?php

namespace Ecodev\Newsletter\Utility;

use Ecodev\Newsletter\Domain\Model\Newsletter;

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
 * Toolbox for newsletter and dependant extensions.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Validator
{
    /**
     * @var \TYPO3\CMS\Lang\LanguageService
     */
    private $lang;

    /**
     * Initialize and return language service
     * @global \TYPO3\CMS\Lang\LanguageService $LANG
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    private function initializeLang()
    {
        // Here we need to include the locallization file for ExtDirect calls, otherwise we get empty strings
        global $LANG;
        if (is_null($LANG)) {
            $LANG = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Lang\LanguageService::class); // create language-object
            $LLkey = 'default';
            if ($GLOBALS['TSFE']->config['config']['language']) {
                $LLkey = $GLOBALS['TSFE']->config['config']['language'];
            }
            $LANG->init($LLkey); // initalize language-object with actual language
        }
        $LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xlf');

        $this->lang = $LANG;
    }

    /**
     * Return the content of the given URL
     * @param string $url
     * @return string
     */
    protected function getURL($url)
    {
        return \Ecodev\Newsletter\Tools::getUrl($url);
    }

    /**
     * Returns the content of the newsletter with validation messages. The content
     * is also "fixed" automatically when possible.
     * @param Newsletter $newsletter
     * @param string $language language of the content of the newsletter (the 'L' parameter in TYPO3 URL)
     * @return array ('content' => $content, 'errors' => $errors, 'warnings' => $warnings, 'infos' => $infos);
     */
    public function validate(Newsletter $newsletter, $language = null)
    {
        $this->initializeLang();

        // We need to catch the exception if domain was not found/configured properly
        try {
            $url = $newsletter->getContentUrl($language);
        } catch (Exception $e) {
            return [
                'content' => '',
                'errors' => [$e->getMessage()],
                'warnings' => [],
                'infos' => [],
            ];
        }

        $content = $this->getURL($url);
        $errors = [];
        $warnings = [];
        $infos = [sprintf($this->lang->getLL('validation_content_url'), '<a target="_blank" href="' . $url . '">' . $url . '</a>')];

        // Content should be more that just a few characters. Apache error propably occured
        if (strlen($content) < 200) {
            $errors [] = $this->lang->getLL('validation_mail_too_short');
        }

        // Content should not contain PHP-Warnings
        if (substr($content, 0, 22) == "<br />\n<b>Warning</b>:") {
            $errors [] = $this->lang->getLL('validation_mail_contains_php_warnings');
        }

        // Content should not contain PHP-Warnings
        if (substr($content, 0, 26) == "<br />\n<b>Fatal error</b>:") {
            $errors [] = $this->lang->getLL('validation_mail_contains_php_errors');
        }

        // If the page contains a "Pages is being generared" text... this is bad too
        if (strpos($content, 'Page is being generated.') && strpos($content, 'If this message does not disappear within')) {
            $errors [] = $this->lang->getLL('validation_mail_being_generated');
        }

        // Find out the absolute domain. If specified in HTML source, use it as is.
        if (preg_match('|<base[^>]*href="([^"]*)"[^>]*/>|i', $content, $match)) {
            $absoluteDomain = $match[1];
        }
        // Otherwise try our best to guess what it is
        else {
            $absoluteDomain = $newsletter->getBaseUrl() . '/';
        }

        // Fix relative URL to absolute URL
        $urlPatterns = [
            'hyperlinks' => '/<a [^>]*href="(.*)"/Ui',
            'stylesheets' => '/<link [^>]*href="(.*)"/Ui',
            'images' => '/ src="(.*)"/Ui',
            'background images' => '/ background="(.*)"/Ui',
        ];
        foreach ($urlPatterns as $type => $urlPattern) {
            preg_match_all($urlPattern, $content, $urls);
            $replacementCount = 0;
            foreach ($urls[1] as $i => $url) {
                // If this is already an absolute link, dont replace it
                $decodedUrl = html_entity_decode($url);
                if (!Uri::isAbsolute($decodedUrl)) {
                    $replace_url = str_replace($decodedUrl, $absoluteDomain . ltrim($decodedUrl, '/'), $urls[0][$i]);
                    $content = str_replace($urls[0][$i], $replace_url, $content);
                    ++$replacementCount;
                }
            }

            if ($replacementCount) {
                $infos[] = sprintf($this->lang->getLL('validation_mail_converted_relative_url'), $type);
            }
        }

        // Find linked css and convert into a style-tag
        preg_match_all('|<link rel="stylesheet" type="text/css" href="([^"]+)"[^>]+>|Ui', $content, $urls);
        foreach ($urls[1] as $i => $url) {
            $content = str_replace($urls[0][$i], "<!-- fetched URL: $url -->
<style type=\"text/css\">\n<!--\n" . $this->getURL($url) . "\n-->\n</style>", $content);
        }
        if (count($urls[1])) {
            $infos[] = $this->lang->getLL('validation_mail_contains_linked_styles');
        }

        // We cant very well have attached javascript in a newsmail ... removing
        $content = preg_replace('|<script[^>]*type="text/javascript"[^>]*>[^<]*</script>|i', '', $content, -1, $count);
        if ($count) {
            $warnings[] = $this->lang->getLL('validation_mail_contains_javascript');
        }

        // Images in CSS
        if (preg_match('|background-image: url\([^\)]+\)|', $content) || preg_match('|list-style-image: url\([^\)]+\)|', $content)) {
            $errors[] = $this->lang->getLL('validation_mail_contains_css_images');
        }

        // CSS-classes
        if (preg_match('|<[a-z]+ [^>]*class="[^"]+"[^>]*>|', $content)) {
            $warnings[] = $this->lang->getLL('validation_mail_contains_css_classes');
        }

        // Positioning & element sizes in CSS
        $forbiddenCssProperties = [
            'width' => '((min|max)+-)?width',
            'height' => '((min|max)+-)?height',
            'margin' => 'margin(-(bottom|left|right|top)+)?',
            'padding' => 'padding(-(bottom|left|right|top)+)?',
            'position' => 'position',
        ];

        $forbiddenCssPropertiesWarnings = [];
        if (preg_match_all('|<[a-z]+[^>]+style="([^"]*)"|', $content, $matches)) {
            foreach ($matches[1] as $stylepart) {
                foreach ($forbiddenCssProperties as $property => $regex) {
                    if (preg_match('/(^|[^\w-])' . $regex . '[^\w-]/', $stylepart)) {
                        $forbiddenCssPropertiesWarnings[$property] = $property;
                    }
                }
            }
            foreach ($forbiddenCssPropertiesWarnings as $property) {
                $warnings[] = sprintf($this->lang->getLL('validation_mail_contains_css_some_property'), $property);
            }
        }

        return [
            'content' => $content,
            'errors' => $errors,
            'warnings' => $warnings,
            'infos' => $infos,
        ];
    }
}
