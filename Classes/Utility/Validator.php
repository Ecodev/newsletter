<?php

namespace Ecodev\Newsletter\Utility;

use Ecodev\Newsletter\Domain\Model\Newsletter;

/**
 * Toolbox for newsletter and dependant extensions.
 */
class Validator
{
    /**
     * @var \TYPO3\CMS\Lang\LanguageService
     */
    private $lang;

    /**
     * @var Newsletter
     */
    private $newsletter;

    /**
     * @var string content to be validated
     */
    private $content;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var array
     */
    private $warnings = [];

    /**
     * @var array
     */
    private $infos = [];

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
     * @return array
     */
    public function validate(Newsletter $newsletter, $language = null)
    {
        $this->initializeLang();

        // Reset stuff
        $this->newsletter = $newsletter;
        $this->content = '';
        $this->errors = [];
        $this->warnings = [];
        $this->infos = [];

        // We need to catch the exception if domain was not found/configured properly
        // or if we can't fetch the content (eg: because of improper SSL certificates)
        try {
            $url = $this->newsletter->getContentUrl($language);
            $this->content = $this->getURL($url);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();

            return $this->getResult();
        }

        $this->infos[] = sprintf($this->lang->getLL('validation_content_url'), '<a target="_blank" href="' . $url . '">' . $url . '</a>');

        $this->errorTooShort();
        $this->errorPhpWarnings();
        $this->errorPhpErrors();
        $this->errorPageBeingGenerated();
        $this->infoRelativeToAbsolute();
        $this->infoLinkedCss();
        $this->warningJavascript();
        $this->errorImageInCSS();
        $this->warningCssClasses();
        $this->warningCssProperties();
        $this->infoImageAlt();

        return $this->getResult();
    }

    /**
     * Content should be more that just a few characters. Apache error propably occured
     */
    private function errorTooShort()
    {
        if (strlen($this->content) < 200) {
            $this->errors[] = $this->lang->getLL('validation_mail_too_short');
        }
    }

    /**
     * Content should not contain PHP-Warnings
     */
    private function errorPhpWarnings()
    {
        if (substr($this->content, 0, 22) == "<br />\n<b>Warning</b>:") {
            $this->errors[] = $this->lang->getLL('validation_mail_contains_php_warnings');
        }
    }

    /**
     * Content should not contain PHP-Warnings
     */
    private function errorPhpErrors()
    {
        if (substr($this->content, 0, 26) == "<br />\n<b>Fatal error</b>:") {
            $this->errors[] = $this->lang->getLL('validation_mail_contains_php_errors');
        }
    }

    /**
     * If the page contains a "Pages is being generared" text, this is bad
     */
    private function errorPageBeingGenerated()
    {
        if (strpos($this->content, 'Page is being generated.') && strpos($this->content, 'If this message does not disappear within')) {
            $this->errors[] = $this->lang->getLL('validation_mail_being_generated');
        }
    }

    /**
     * Fix relative URL to absolute URL
     */
    private function infoRelativeToAbsolute()
    {
        // Find out the absolute domain. If specified in HTML source, use it as is.
        if (preg_match('|<base[^>]*href="([^"]*)"[^>]*/>|i', $this->content, $match)) {
            $absoluteDomain = $match[1];
        }
        // Otherwise try our best to guess what it is
        else {
            $absoluteDomain = $this->newsletter->getBaseUrl() . '/';
        }

        $urlPatterns = [
            'hyperlinks' => '/<a [^>]*href="(.*)"/Ui',
            'stylesheets' => '/<link [^>]*href="(.*)"/Ui',
            'images' => '/ src="(.*)"/Ui',
            'background images' => '/ background="(.*)"/Ui',
        ];

        foreach ($urlPatterns as $type => $urlPattern) {
            preg_match_all($urlPattern, $this->content, $urls);
            $replacementCount = 0;
            foreach ($urls[1] as $i => $url) {
                // If this is already an absolute link, dont replace it
                $decodedUrl = html_entity_decode($url);
                if (!Uri::isAbsolute($decodedUrl)) {
                    $replace_url = str_replace($decodedUrl, $absoluteDomain . ltrim($decodedUrl, '/'), $urls[0][$i]);
                    $this->content = str_replace($urls[0][$i], $replace_url, $this->content);
                    ++$replacementCount;
                }
            }

            if ($replacementCount) {
                $this->infos[] = sprintf($this->lang->getLL('validation_mail_converted_relative_url'), $type);
            }
        }
    }

    /**
     * Find linked css and convert into a <style> tag
     */
    private function infoLinkedCss()
    {
        preg_match_all('|<link rel="stylesheet" type="text/css" href="([^"]+)"[^>]+>|Ui', $this->content, $urls);
        foreach ($urls[1] as $i => $url) {
            $this->content = str_replace($urls[0][$i], "<!-- fetched URL: $url -->
<style type=\"text/css\">\n<!--\n" . $this->getURL($url) . "\n-->\n</style>", $this->content);
        }

        if (count($urls[1])) {
            $this->infos[] = $this->lang->getLL('validation_mail_contains_linked_styles');
        }
    }

    /**
     * We cant very well have attached javascript in a newsmail ... removing
     */
    private function warningJavascript()
    {
        $this->content = preg_replace('|<script[^>]*type="text/javascript"[^>]*>[^<]*</script>|i', '', $this->content, -1, $count);
        if ($count) {
            $this->warnings[] = $this->lang->getLL('validation_mail_contains_javascript');
        }
    }

    /**
     * Find images used via CSS
     */
    private function errorImageInCSS()
    {
        if (preg_match('|background-image: url\([^\)]+\)|', $this->content) || preg_match('|list-style-image: url\([^\)]+\)|', $this->content)) {
            $this->errors[] = $this->lang->getLL('validation_mail_contains_css_images');
        }
    }

    /**
     * Find CSS classes
     */
    private function warningCssClasses()
    {
        if (preg_match('|<[a-z]+ [^>]*class="[^"]+"[^>]*>|', $this->content)) {
            $this->warnings[] = $this->lang->getLL('validation_mail_contains_css_classes');
        }
    }

    /**
     * Find forbidden CSS properties
     */
    private function warningCssProperties()
    {
        // Positioning & element sizes in CSS
        $forbiddenCssProperties = [
            'width' => '((min|max)+-)?width',
            'height' => '((min|max)+-)?height',
            'margin' => 'margin(-(bottom|left|right|top)+)?',
            'padding' => 'padding(-(bottom|left|right|top)+)?',
            'position' => 'position',
        ];

        $forbiddenCssPropertiesWarnings = [];
        if (preg_match_all('|<[a-z]+[^>]+style="([^"]*)"|', $this->content, $matches)) {
            foreach ($matches[1] as $stylepart) {
                foreach ($forbiddenCssProperties as $property => $regex) {
                    if (preg_match('/(^|[^\w-])' . $regex . '[^\w-]/', $stylepart)) {
                        $forbiddenCssPropertiesWarnings[$property] = $property;
                    }
                }
            }
            foreach ($forbiddenCssPropertiesWarnings as $property) {
                $this->warnings[] = sprintf($this->lang->getLL('validation_mail_contains_css_some_property'), $property);
            }
        }
    }

    /**
     * Inject alt attribute for image that don't have it yet
     */
    private function infoImageAlt()
    {
        if (!$this->content) {
            return;
        }

        $document = new \DOMDocument();
        @$document->loadHTML($this->content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imgs = $document->getElementsByTagName('img');
        $count = 0;
        foreach ($imgs as $img) {
            if (!$img->hasAttribute('alt')) {
                $img->setAttribute('alt', '');
                ++$count;
            }
        }

        if ($count) {
            $this->infos[] = sprintf($this->lang->getLL('validation_mail_injected_alt_attribute'), $count);
        }

        $this->content = trim($document->saveHTML());
    }

    /**
     * Return the structured result
     * @return array ['content' => $content, 'errors' => $errors, 'warnings' => $warnings, 'infos' => $infos]
     */
    private function getResult()
    {
        return [
            'content' => $this->content,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'infos' => $this->infos,
        ];
    }
}
