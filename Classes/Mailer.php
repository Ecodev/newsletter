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

require_once(PATH_typo3 . 'contrib/swiftmailer/swift_required.php');

/**
 * This is the holy inner core of newsletter.
 * It is normally used in an instance per language to compile MIME 1.0 compatible mails
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Mailer
{

    private $html;
    private $html_tpl;
    private $plain;
    private $plain_tpl;
    private $title;
    private $title_tpl;
    private $senderName;
    private $senderEmail;
    private $bounceAddress;
    private $siteUrl;
    private $homeUrl;
    private $attachments = array();
    private $attachmentsEmbedded = array();
    private $linksCache = array();

    /**
     * Constructor that set up basic internal datastructures. Do not call directly
     *
     */
    public function __construct()
    {
        global $TYPO3_CONF_VARS;

        /* Read some basic settings */
        $this->extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['newsletter']);
        $this->realPath = PATH_site;
    }

    /**
     * Returns the current HTML content
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Returns the current Plain Text content
     */
    public function getPlain()
    {
        return $this->plain;
    }

    public function setNewsletter(Tx_Newsletter_Domain_Model_Newsletter $newsletter, $language = null)
    {
        $domain = $newsletter->getDomain();

        // When sending newsletter via scheduler (so via CLI mode) realurl cannot guess
        // the domain name by himself, so we help him by filling HTTP_HOST variable
        $_SERVER['HTTP_HOST'] = $domain;
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $this->siteUrl = "http://$domain/";
        $this->linksCache = array();
        $this->newsletter = $newsletter;
        $this->homeUrl = $this->siteUrl . t3lib_extMgm::siteRelPath('newsletter');
        $this->senderName = $newsletter->getSenderName();
        $this->senderEmail = $newsletter->getSenderEmail();
        $bounceAccount = $newsletter->getBounceAccount();
        $this->bounceAddress = $bounceAccount ? $bounceAccount->getEmail() : '';

        // Build html
        $validatedContent = $newsletter->getValidatedContent($language);
        if (count($validatedContent['errors'])) {
            throw new Exception('The newsletter HTML content does not validate. The sending is aborted. See errors: ' . serialize($validatedContent['errors']));
        }
        $this->setHtml($validatedContent['content']);

        // Build title from HTML source (we cannot use $newsletter->getTitle(), because it is NOT localized)
        $this->setTitle($validatedContent['content']);

        // Build plaintext
        $plain = $newsletter->getPlainConverterInstance();
        $plain->setContent($validatedContent['content'], $newsletter->getContentUrl($language), $this->domain);
        $this->setPlain($plain->getPlaintext());

        // Attaching files
        $files = $newsletter->getAttachments();
        foreach ($files as $file) {
            if (trim($file) != '') {
                $filename = PATH_site . "uploads/tx_newsletter/$file";
                $this->attachments[] = Swift_Attachment::fromPath($filename);
            }
        }
    }

    /**
     * Extract the title from <title></title> HTML tags
     * @param string $htmlSrc
     */
    private function setTitle($htmlSrc)
    {
        // Extract title from HTML
        preg_match('|<title[^>]*>(.*)</title>|i', $htmlSrc, $m);
        $title = trim($m[1]);

        /* Detect what markers we need to substitute later on */
        preg_match_all('/###[\w]+###/', $title, $fields);
        $this->titleMarkers = str_replace('###', '', $fields[0]);

        /* Any advanced markers we need to sustitute later on */
        $this->titleAdvancedMarkers = array();
        preg_match_all('/###:IF: (\w+) ###/U', $title, $fields);
        foreach ($fields[1] as $field) {
            $this->titleAdvancedMarkers[] = $field;
        }

        $this->title_tpl = $title;
        $this->title = $title;
    }

    /**
     * Set the plain text content on the mail
     *
     * @param   string      The plain text content of the mail
     * @return   void
     */
    private function setPlain($src)
    {

        /* Detect what markers we need to substitute later on */
        preg_match_all('/###(\w+)###/', $src, $fields);
        preg_match_all('|http://(\w+)\s|', $src, $fieldsLinks);
        $this->plainMarkers = array_merge($fields[1], $fieldsLinks[1]);

        /* Any advanced markers we need to sustitute later on */
        $this->plainAdvancedMarkers = array();
        preg_match_all('/###:IF: (\w+) ###/U', $src, $fields);
        foreach ($fields[1] as $field) {
            $this->plainAdvancedMarkers[] = $field;
        }

        $this->plain_tpl = $src;
        $this->plain = $src;
    }

    /**
     * Set the html content of the mail which will be used as template.
     * The content will be edited to include images as attachements if needed.
     *
     * @param   string      The html content of the mail
     * @return   void
     */
    private function setHtml($src)
    {

        // Convert external files resources to attached files or correct their links
        $replace_regs = array(
            '/ src="([^"]+)"/',
            '/ background="([^"]+)"/',
        );

        // Attach images if option is set
        if ($this->extConf['attach_images']) {
            foreach ($replace_regs as $replace_reg) {
                preg_match_all($replace_reg, $src, $urls);
                foreach ($urls[1] as $i => $url) {

                    // Mark places for embedded files and keep the embed files to be replaced
                    $swiftEmbeddedMarker = '###_#_SWIFT_EMBEDDED_MARKER_' . count($this->attachmentsEmbedded) . '_#_###';
                    $this->attachmentsEmbedded[$swiftEmbeddedMarker] = Swift_EmbeddedFile::fromPath($url);
                    $src = str_replace($urls[0][$i], str_replace($url, $swiftEmbeddedMarker, $urls[0][$i]), $src);
                }
            }
        }

        // Detect what markers we need to substitute later on
        preg_match_all('/###(\w+)###/', $src, $fields);
        preg_match_all('|"http://(\w+)"|', $src, $fieldsLinks);
        $this->htmlMarkers = array_merge($fields[1], $fieldsLinks[1]);

        // Any advanced IF fields we need to sustitute later on
        $this->htmlAdvancedMarkers = array();
        preg_match_all('/###:IF: (\w+) ###/U', $src, $fields);
        foreach ($fields[1] as $field) {
            $this->htmlAdvancedMarkers[] = $field;
        }

        $this->html_tpl = $src;
        $this->html = $src;
    }

    /**
     * Insert a "mail-open-spy" in the mail.
     *
     * @return   void
     */
    private function injectOpenSpy(Tx_Newsletter_Domain_Model_Email $email)
    {
        $this->html = str_ireplace(
                '</body>', '<div><img src="' . Tx_Newsletter_Tools::buildFrontendUri('opened', array(), 'Email') . '&c=' . $email->getAuthCode() . '" width="0" height="0" /></div></body>', $this->html);
    }

    /**
     * Reset all modifications to the content.
     *
     * @return   void
     */
    private function resetMarkers()
    {
        $this->html = $this->html_tpl;
        $this->plain = $this->plain_tpl;
        $this->title = $this->title_tpl;
    }

    /**
     * Replace a named marker with a suppied value.
     * A marker can have the form of a simple string marker ###marker###, or http://marker
     * Or an advanced conditionnal marker ###:IF: marker ### ..content.. (###:ELSE:###)? ..content.. ###:ENDIF:###
     *
     * @param   string      Name of the marker to replace
     * @param   string      Value to replace marker with.
     * @return   void
     */
    private function substituteMarker($name, $value)
    {
        // For each marker, only substitute if the field is registered as a marker.
        // This approach has shown to speed up things quite a bit.
        if (in_array($name, $this->htmlAdvancedMarkers)) {
            $this->html = self::advancedSubstituteMarker($this->html, $name, $value);
        }

        if (in_array($name, $this->plainAdvancedMarkers)) {
            $this->plain = self::advancedSubstituteMarker($this->plain, $name, $value);
        }

        if (in_array($name, $this->titleAdvancedMarkers)) {
            $this->title = self::advancedSubstituteMarker($this->title, $name, $value);
        }

        // All variants of the marker to search
        $search = array(
            "###$name###",
            "http://$name",
            urlencode("http://$name"), // If the marker is in a link and the "links spy" option is activated it will be urlencoded
        );

        $replace = array(
            $value,
            $value,
            urlencode($value), // We need to replace with urlencoded value
        );

        if (in_array($name, $this->htmlMarkers)) {
            $this->html = str_ireplace($search, $replace, $this->html);
        }

        if (in_array($name, $this->plainMarkers)) {
            $this->plain = str_ireplace($search, $replace, $this->plain);
        }

        if (in_array($name, $this->titleMarkers)) {
            $this->title = str_ireplace($search, $replace, $this->title);
        }
    }

    /**
     * Substitute an advanced marker.
     *
     * @internal
     * @param   string      Source to apply marker substitution to.
     * @param   string      Name of marker.
     * @param   boolean      Display value of marker.
     * @return   string      Source with applied marker.
     */
    private function advancedSubstituteMarker($src, $name, $value)
    {
        preg_match_all("/###:IF: $name ###([\w\W]*)###:ELSE:###([\w\W]*)###:ENDIF:###/U", $src, $matches);
        foreach ($matches[0] as $i => $full_mark) {
            if ($value) {
                $src = str_ireplace($full_mark, $matches[1][$i], $src);
            } else {
                $src = str_ireplace($full_mark, $matches[2][$i], $src);
            }
        }

        preg_match_all("/###:IF: $name ###([\w\W]*)###:ENDIF:###/U", $src, $matches);
        foreach ($matches[0] as $i => $full_mark) {
            if ($value) {
                $src = str_ireplace($full_mark, $matches[1][$i], $src);
            } else {
                $src = str_ireplace($full_mark, '', $src);
            }
        }

        return $src;
    }

    /**
     * Apply multiple markers to mail contents
     *
     * @param   array      Assoc array with name => value pairs.
     * @return   void
     */
    private function substituteMarkers(Tx_Newsletter_Domain_Model_Email $email)
    {
        $markers = $email->getRecipientData();

        // Add predefined markers
        $authCode = $email->getAuthCode();
        $markers['newsletter_view_url'] = Tx_Newsletter_Tools::buildFrontendUri('show', array(), 'Email') . '&c=' . $authCode;
        $markers['newsletter_unsubscribe_url'] = Tx_Newsletter_Tools::buildFrontendUri('unsubscribe', array(), 'Email') . '&c=' . $authCode;

        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['substituteMarkersHook'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newsletter']['substituteMarkersHook'] as $_classRef) {
                $_procObj = & t3lib_div::getUserObj($_classRef);
                $this->html = $_procObj->substituteMarkersHook($this->html, 'html', $markers);
                $this->plain = $_procObj->substituteMarkersHook($this->plain, 'plain', $markers);
                $this->title = $_procObj->substituteMarkersHook($this->title, 'title', $markers);
            }
        }

        foreach ($markers as $name => $value) {
            $this->substituteMarker($name, $value);
        }
    }

    private function getLinkAuthCode(Tx_Newsletter_Domain_Model_Email $email, $url, $isPreview, $isPlainText = false)
    {
        global $TYPO3_DB;
        $url = html_entity_decode($url);

        // First check in our local cache
        if (isset($this->linksCache[$url])) {
            $linkId = $this->linksCache[$url];
        }
        // Otherwise if we are preparing a preview, just generate incremental ID and do not touch database at all
        elseif ($isPreview) {
            $linkId = count($this->linksCache);
        }
        // Finally if we it's not a preview and link was not in cache, check database
        else {
            // Look for the link database, it may already exist
            $res = $TYPO3_DB->sql_query('SELECT uid FROM tx_newsletter_domain_model_link WHERE url = "' . $url . '" AND newsletter = ' . $this->newsletter->getUid() . ' LIMIT 1');
            $row = $TYPO3_DB->sql_fetch_row($res);
            if ($row) {
                $linkId = $row[0];
            }
            // Otherwise create it
            else {
                $TYPO3_DB->exec_INSERTquery('tx_newsletter_domain_model_link', array(
                    'pid' => $this->newsletter->getPid(),
                    'url' => $url,
                    'newsletter' => $this->newsletter->getUid(),
                ));

                $linkId = $TYPO3_DB->sql_insert_id();
            }
        }

        // Store link in cache
        $this->linksCache[$url] = $linkId;

        $authCode = md5($email->getAuthCode() . $linkId);
        $newUrl = Tx_Newsletter_Tools::buildFrontendUri('clicked', array(), 'Link') . '&url=' . urlencode($url) . '&l=' . $authCode . ($isPlainText ? '&p=1' : '');

        return $newUrl;
    }

    /**
     * Replace all links in the mail to make spy links.
     *
     * @param Tx_Newsletter_Domain_Model_Email $email The email to prepare the newsletter for
     * @param boolean $isPreview whether we are preparing a preview version (if true links will not be stored in database thus no statistics will be available)
     * @return   void
     */
    private function injectLinksSpy(Tx_Newsletter_Domain_Model_Email $email, $isPreview)
    {
        /* Exchange all http:// links  html */
        preg_match_all('|<a [^>]*href="(https?://[^"]*)"|Ui', $this->html, $urls);
        foreach ($urls[1] as $i => $url) {
            $newUrl = $this->getLinkAuthCode($email, $url, $isPreview);

            /* Two step replace to be as precise as possible */
            $link = str_replace($url, $newUrl, $urls[0][$i]);
            $this->html = str_replace($urls[0][$i], $link, $this->html);
        }

        /* Exchange all http:// links plaintext */
        preg_match_all('|https?://[^ \r\n\)]*|i', $this->plain, $urls, PREG_OFFSET_CAPTURE);
        $changedOffset = 0;
        foreach ($urls[0] as $i => $url) {
            $newUrl = $this->getLinkAuthCode($email, $url[0], $isPreview, true);
            $this->plain = substr_replace($this->plain, $newUrl, $url[1] + $changedOffset, strlen($url[0]));
            $changedOffset += strlen($newUrl) - strlen($url[0]);
        }
    }

    /**
     * Prepare the newsletter content for the specified email (substitute markers and insert spies)
     * @param Tx_Newsletter_Domain_Model_Email $email
     * @param boolean $isPreview whether we are preparing a preview version of the newsletter
     */
    public function prepare(Tx_Newsletter_Domain_Model_Email $email, $isPreview = false)
    {
        $this->resetMarkers();

        if ($this->newsletter->getInjectOpenSpy())
            $this->injectOpenSpy($email);

        if ($this->newsletter->getInjectLinksSpy())
            $this->injectLinksSpy($email, $isPreview);

        // We substitute markers last because we don't want to spy each links to view/unsubscribe
        // (created via markers) for each recipient. Only the generic marker is enough.
        // Otherwise we would mess up opened link statistics
        $this->substituteMarkers($email);
    }

    /**
     * The regular send method. Use this to send a normal, personalized mail.
     *
     * @param Tx_Newsletter_Domain_Model_Email $email The email object containing recipient email address and extra data for markers
     * @return   void
     */
    public function send(Tx_Newsletter_Domain_Model_Email $email)
    {
        $this->prepare($email);
        $this->raw_send($email);
    }

    /**
     * Raw send method. This does not replace markers, or reset the mail afterwards.
     *
     * @interal
     * @param   array      Record with receivers information as name => value pairs.
     * @param   array      Array with extra headers to apply to mails as name => value pairs.
     * @return   void
     */
    private function raw_send(Tx_Newsletter_Domain_Model_Email $email)
    {
        $message = t3lib_div::makeInstance('t3lib_mail_Message');
        $message->setTo($email->getRecipientAddress())
                ->setFrom(array($this->senderEmail => $this->senderName))
                ->setSubject($this->title)
        ;

        if ($this->bounceAddress) {
            $message->setReturnPath($this->bounceAddress);
        }

        foreach ($this->attachments as $attachment) {
            $message->attach($attachment);
        }

        // Specify message-id for bounce identification
        $msgId = $message->getHeaders()->get('Message-ID');
        $msgId->setId($email->getAuthCode() . '@' . $this->newsletter->getDomain());

        $recipientData = $email->getRecipientData();
        if ($recipientData['plain_only']) {
            $message->setBody($this->plain, 'text/plain');
        } else {
            // Attach inline files and replace markers used for URL
            foreach ($this->attachmentsEmbedded as $marker => $attachment) {
                $embeddedSrc = $message->embed($attachment);
                $this->plain = str_replace($marker, $embeddedSrc, $this->plain);
                $this->html = str_replace($marker, $embeddedSrc, $this->html);
            }

            $message->setBody($this->html, 'text/html');
            $message->addPart($this->plain, 'text/plain');
        }

        $message->send();
    }

}
