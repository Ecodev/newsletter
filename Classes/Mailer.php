<?php

namespace Ecodev\Newsletter;

use Ecodev\Newsletter\Domain\Model\Email;
use Ecodev\Newsletter\Domain\Model\Newsletter;
use Ecodev\Newsletter\Utility\UriBuilder;
use Swift_Attachment;
use Swift_EmbeddedFile;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This is the holy inner core of newsletter.
 * It is normally used in an instance per language to compile MIME 1.0 compatible mails
 */
class Mailer
{
    /**
     * @var Newsletter
     */
    private $newsletter;
    private $html;
    private $htmlTemplate;
    private $title;
    private $titleTemplate;
    private $senderName;
    private $senderEmail;
    private $replytoName;
    private $replytoEmail;
    private $bounceAddress;
    private $siteUrl;
    private $homeUrl;
    private $attachments = [];
    private $attachmentsEmbedded = [];
    private $attachmentsMapping = [];
    private $linksCache = [];

    /**
     * @var Utility\MarkerSubstitutor
     */
    private $substitutor;

    /**
     * Cached domain name
     *
     * @var string
     */
    private $domain;

    /**
     * Constructor that set up basic internal data structures. Do not call directly
     */
    public function __construct()
    {
        global $TYPO3_CONF_VARS;

        /* Read some basic settings */
        $this->extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['newsletter']);
        $this->realPath = PATH_site;
        $this->substitutor = new Utility\MarkerSubstitutor();
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
        $plainConverter = $this->newsletter->getPlainConverterInstance();
        $plainText = $plainConverter->getPlaintext($this->getHtml(), $this->domain);

        return $plainText;
    }

    /**
     * Sets the newsletter
     *
     * @param Newsletter $newsletter
     * @param string $language
     *
     * @throws \Exception
     */
    public function setNewsletter(Newsletter $newsletter, $language = null)
    {
        $this->domain = $newsletter->getDomain();
        $this->siteUrl = $newsletter->getBaseUrl() . '/';
        $this->linksCache = [];
        $this->newsletter = $newsletter;
        $this->homeUrl = $this->siteUrl . ExtensionManagementUtility::siteRelPath('newsletter');
        $this->senderName = $newsletter->getSenderName();
        $this->senderEmail = $newsletter->getSenderEmail();
        $this->replytoName = $newsletter->getReplytoName();
        $this->replytoEmail = $newsletter->getReplytoEmail();
        $bounceAccount = $newsletter->getBounceAccount();
        $this->bounceAddress = $bounceAccount ? $bounceAccount->getEmail() : null;
        $this->setupCli();

        // Build html
        $validatedContent = $newsletter->getValidatedContent($language);
        if (count($validatedContent['errors'])) {
            throw new \Exception('The newsletter HTML content does not validate. The sending is aborted. See errors: ' . serialize($validatedContent['errors']));
        }
        $this->setHtml($validatedContent['content']);

        // Build title from HTML source (we cannot use $newsletter->getTitle(), because it is NOT localized)
        $this->setTitle($validatedContent['content']);

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
     * When sending newsletter via scheduler (so via CLI mode) core and realurl cannot guess
     * the domain name by themselves, so we help them by filling HTTP_HOST variable and a
     * a few other things.
     */
    private function setupCli()
    {
        $_SERVER['HTTP_HOST'] = $this->domain;
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        if (!defined('TYPO3_PATH_WEB')) {
            define('TYPO3_PATH_WEB', true);
        }

        // Event though this is an internal method we must empty env variables cache,
        // because it was already filled with incorrect values
        GeneralUtility::flushInternalRuntimeCaches();
    }

    /**
     * Extract the title from <title></title> HTML tags
     *
     * @param string $htmlSrc
     */
    private function setTitle($htmlSrc)
    {
        // Extract title from HTML
        preg_match('|<title[^>]*>(.*)</title>|i', $htmlSrc, $m);

        // As this is being extracted from HTML and it is being used as an email subject we need to decode any entities.
        $title = trim(html_entity_decode($m[1]));

        $this->titleTemplate = $title;
        $this->title = $title;
    }

    /**
     * Set the html content of the mail which will be used as template.
     * The content will be edited to include images as attachements if needed.
     *
     * @param string $src The HTML content of the mail
     */
    private function setHtml($src)
    {
        $src = $this->findAttachments($src);
        $this->htmlTemplate = $src;
        $this->html = $src;
    }

    /**
     * Find and memorize attachments that will need to be processed by Swift
     *
     * @param string $src
     *
     * @return string
     */
    private function findAttachments($src)
    {
        // Attach images if option is set
        if (!$this->extConf['attach_images']) {
            return $src;
        }

        // Convert external files resources to attached files
        $attachmentRegexes = [
            '/ src="([^"]+)"/',
            '/ background="([^"]+)"/',
        ];

        foreach ($attachmentRegexes as $regex) {
            preg_match_all($regex, $src, $urls);
            foreach ($urls[1] as $i => $url) {
                // Mark places for embedded files
                $swiftEmbeddedMarker = $this->getSwiftEmbeddedMarker($url);
                if ($swiftEmbeddedMarker) {
                    $src = str_replace($urls[0][$i], str_replace($url, $swiftEmbeddedMarker, $urls[0][$i]), $src);
                }
            }
        }

        return $src;
    }

    /**
     * Returns a swift marker if the image can be embedded
     *
     * @param string $imageUrl
     *
     * @return string|null
     */
    private function getSwiftEmbeddedMarker($imageUrl)
    {
        // Get filesystem path from url
        $relativePath = str_replace($this->siteUrl, '', $imageUrl);
        $absolutePath = GeneralUtility::getFileAbsFileName($relativePath);

        // If the same image was already embeded, reuse its marker, otherwise create a marker and keep the embed files to be replaced
        if (isset($this->attachmentsMapping[$absolutePath])) {
            return $this->attachmentsMapping[$absolutePath];
        } elseif (file_exists($absolutePath)) {
            $swiftEmbeddedMarker = '###_#_SWIFT_EMBEDDED_MARKER_' . count($this->attachmentsEmbedded) . '_#_###';
            $this->attachmentsEmbedded[$swiftEmbeddedMarker] = Swift_EmbeddedFile::fromPath($absolutePath);
            $this->attachmentsMapping[$absolutePath] = $swiftEmbeddedMarker;

            return $swiftEmbeddedMarker;
        }
    }

    /**
     * Insert a "mail-open-spy" in the mail.
     *
     * @param Email $email
     */
    private function injectOpenSpy(Email $email)
    {
        $url = $email->getOpenedUrl();

        $this->html = str_ireplace('</body>', '<div><img src="' . $url . '" width="0" height="0" /></div></body>', $this->html);
    }

    /**
     * Reset all modifications to the content.
     */
    private function resetContent()
    {
        $this->html = $this->htmlTemplate;
        $this->title = $this->titleTemplate;
    }

    /**
     * Apply multiple markers to mail contents
     *
     * @param Email $email
     */
    private function substituteMarkers(Email $email)
    {
        $this->html = $this->substitutor->substituteMarkers($this->html, $email, 'html');
        $this->title = $this->substitutor->substituteMarkers($this->title, $email, 'title');
    }

    /**
     * Get the link with auth code.
     *
     * @param Email $email
     * @param string $url
     * @param bool $isPreview
     * @param bool $isPlainText
     *
     * @return string The link url
     */
    private function getLinkAuthCode(Email $email, $url, $isPreview, $isPlainText = false)
    {
        $db = Tools::getDatabaseConnection();
        $url = html_entity_decode($url);

        // First check in our local cache
        if (isset($this->linksCache[$url])) {
            $linkId = $this->linksCache[$url];
        } // Otherwise if we are preparing a preview, just generate incremental ID and do not touch database at all
        elseif ($isPreview) {
            $linkId = count($this->linksCache);
        } // Finally if it's not a preview and link was not in cache, check database
        else {
            // Look for the link database, it may already exist
            $res = $db->sql_query('SELECT uid FROM tx_newsletter_domain_model_link WHERE url = ' . $db->fullQuoteStr($url, 'tx_newsletter_domain_model_link') . ' AND newsletter = ' . $db->fullQuoteStr($this->newsletter->getUid(), 'tx_newsletter_domain_model_link') . ' LIMIT 1');
            $row = $db->sql_fetch_row($res);
            if ($row) {
                $linkId = $row[0];
            } // Otherwise create it
            else {
                $db->exec_INSERTquery('tx_newsletter_domain_model_link', [
                    'pid' => $this->newsletter->getPid(),
                    'url' => $url,
                    'newsletter' => $this->newsletter->getUid(),
                ]);

                $linkId = $db->sql_insert_id();
            }
        }

        // Store link in cache
        $this->linksCache[$url] = $linkId;

        $authCode = md5($email->getAuthCode() . $linkId);
        $arguments = [];
        $arguments['n'] = $this->newsletter->getUid();
        $arguments['l'] = $authCode;
        if ($isPlainText) {
            $arguments['p'] = 1;
        }
        $newUrl = UriBuilder::buildFrontendUri($email->getPid(), 'Link', 'clicked', $arguments);

        return $newUrl;
    }

    /**
     * Replace all links in the mail to make spy links.
     *
     * @param Email $email The email to prepare the newsletter for
     * @param bool $isPreview whether we are preparing a preview version (if true links will not be stored in database thus no statistics will be available)
     */
    private function injectLinksSpy(Email $email, $isPreview)
    {
        /* Exchange all http:// links  html */
        preg_match_all('|<a [^>]*href="(https?://[^"]*)"|Ui', $this->html, $urls);
        // No-Track Marker
        $noTrackMarker = Tools::confParam('no-track');
        foreach ($urls[1] as $i => $url) {
            // Check for a no-track marker
            if (!empty($noTrackMarker) && mb_stripos($url, $noTrackMarker) !== false) {
                continue;
            }

            $newUrl = $this->getLinkAuthCode($email, $url, $isPreview);

            /* Two step replace to be as precise as possible */
            $link = str_replace($url, $newUrl, $urls[0][$i]);
            $this->html = str_replace($urls[0][$i], $link, $this->html);
        }
    }

    /**
     * Prepare the newsletter content for the specified email (substitute markers and insert spies)
     *
     * @param Email $email
     * @param bool $isPreview whether we are preparing a preview version of the newsletter
     */
    public function prepare(Email $email, $isPreview = false)
    {
        $this->resetContent();

        if ($this->newsletter->getInjectOpenSpy()) {
            $this->injectOpenSpy($email);
        }

        if ($this->newsletter->getInjectLinksSpy()) {
            $this->injectLinksSpy($email, $isPreview);
        }

        // We substitute markers last because we don't want to spy each links to view/unsubscribe
        // (created via markers) for each recipient. Only the generic marker is enough.
        // Otherwise we would mess up opened link statistics
        $this->substituteMarkers($email);
    }

    /**
     * The regular send method. Use this to send a normal, personalized mail.
     *
     * @param Email $email The email object containing recipient email address and extra data for markers
     */
    public function send(Email $email)
    {
        $this->prepare($email);
        $message = $this->createMessage($email);
        $message->send();
    }

    /**
     * Creates the Message object from our current state and returns it
     *
     * @param Email $email
     *
     * @return MailMessage
     */
    public function createMessage(Email $email)
    {
        // Possibly override sender info from recipientData
        $recipientData = $email->getRecipientData();
        $senderEmail = isset($recipientData['sender_email']) && GeneralUtility::validEmail($recipientData['sender_email']) ? $recipientData['sender_email'] : $this->senderEmail;
        $senderName = isset($recipientData['sender_name']) && $recipientData['sender_name'] ? $recipientData['sender_name'] : $this->senderName;
        $replytoEmail = isset($recipientData['replyto_email']) && GeneralUtility::validEmail($recipientData['replyto_email']) ? $recipientData['replyto_email'] : $this->replytoEmail;
        $replytoName = isset($recipientData['replyto_name']) && $recipientData['replyto_name'] ? $recipientData['replyto_name'] : $this->replytoName;

        /* @var $message MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setTo($email->getRecipientAddress())
            ->setFrom([
                $senderEmail => $senderName,
            ])
            ->setSubject($this->title);

        if ($replytoEmail) {
            $message->addReplyTo($replytoEmail, $replytoName);
        }

        $unsubscribeUrls = ['<' . $email->getUnsubscribeUrl() . '>'];
        if ($this->bounceAddress) {
            $message->setReturnPath($this->bounceAddress);
            array_unshift($unsubscribeUrls, '<mailto:' . $this->bounceAddress . '?subject=unsubscribe-' . $email->getAuthCode() . '>');
        }

        // Add header for easy unsubscribe, either by email, or standard URL
        $message->getHeaders()->addTextHeader('List-Unsubscribe', implode(', ', $unsubscribeUrls));
        $message->getHeaders()->addTextHeader('Precedence', 'bulk');

        foreach ($this->attachments as $attachment) {
            $message->attach($attachment);
        }

        // Specify message-id for bounce identification
        $msgId = $message->getHeaders()->get('Message-ID');
        $msgId->setId($email->getAuthCode() . '@' . $this->domain);

        // Build plaintext
        $plain = $this->getPlain();

        if ($recipientData['plain_only']) {
            $message->setBody($plain, 'text/plain');
        } else {
            // Attach inline files and replace markers used for URL
            foreach ($this->attachmentsEmbedded as $marker => $attachment) {
                $embeddedSrc = $message->embed($attachment);
                $plain = str_replace($marker, $embeddedSrc, $plain);
                $this->html = str_replace($marker, $embeddedSrc, $this->html);
            }

            $message->setBody($this->html, 'text/html');
            $message->addPart($plain, 'text/plain');
        }

        return $message;
    }
}
