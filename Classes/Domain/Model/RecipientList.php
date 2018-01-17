<?php

namespace Ecodev\Newsletter\Domain\Model;

use Ecodev\Newsletter\Utility\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * RecipientList
 */
abstract class RecipientList extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * plainOnly
     *
     * @var bool
     */
    protected $plainOnly = false;

    /**
     * lang
     *
     * @var int
     */
    protected $lang = 0;

    /**
     * type
     *
     * @var string
     */
    protected $type = '';

    /**
     * Setter for title
     *
     * @param string $title title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Getter for title
     *
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Setter for plainOnly
     *
     * @param bool $plainOnly plainOnly
     */
    public function setPlainOnly($plainOnly)
    {
        $this->plainOnly = $plainOnly;
    }

    /**
     * Getter for plainOnly
     *
     * @return bool plainOnly
     */
    public function getPlainOnly()
    {
        return $this->plainOnly;
    }

    /**
     * Returns the state of plainOnly
     *
     * @return bool the state of plainOnly
     */
    public function isPlainOnly()
    {
        return $this->getPlainOnly();
    }

    /**
     * Setter for lang
     *
     * @param int $lang lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Getter for lang
     *
     * @return int lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Setter for type
     *
     * @param string $type type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Getter for type
     *
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Array or mysql result containing raw data for recipient list. Kind of cache in memory.
     *
     * @var array
     */
    protected $data = null;

    /**
     * Initializing method to prepare for reading recipients.
     */
    abstract public function init();

    /**
     * Fetch one receiver record from the newsletter target.
     * The record MUST contain an "email"-field. Without this one this mailtarget is useless.
     *
     * @return array|false Associative array with fields for the receiver
     */
    abstract public function getRecipient();

    /**
     * Get the number of receivers in this newsletter target
     *
     * @return int numbers of receivers
     */
    abstract public function getCount();

    /**
     * Get error text if the fetching of the newsletter target has somehow failed.
     *
     * @return string error text or empty string
     */
    abstract public function getError();

    /**
     * Here you can define an action when an address bounces. This can be database operations such as a deletion.
     * For external data-sources, you might consider collecting the addresses for later removal from the foreign system.
     * The \Ecodev\Newsletter\Domain\Model\RecipientList\GentleSql implements a sensible default.
     *
     * @param string $email the email address of the recipient
     * @param int $bounceLevel Level of bounce, @see \Ecodev\Newsletter\BounceHandler for possible values
     *
     * @return bool status of the success of the removal
     */
    public function registerBounce($email, $bounceLevel)
    {
        return false;
    }

    /**
     * Here you can implement some action to take whenever the user has opened the mail via beenthere.php
     *
     * @param string $email the email address of the recipient (who opened the mail)
     */
    public function registerOpen($email)
    {
    }

    /**
     * Here you can implement some action to take whenever the user has clicked a link via click.php
     *
     * @param string $email the email address of the recipient
     */
    public function registerClick($email)
    {
    }

    /**
     * Return HTML code showing an extract of recipients (first X recipients)
     *
     * @param int $limit
     */
    public function getExtract($limit = 30)
    {
        if ($this->getError()) {
            $out = 'Error: ' . $this->getError();
        } else {
            $i = 0;
            while ($row = $this->getRecipient()) {
                // Dump formatted table header
                if ($i == 0) {
                    $out .= '<tr>';
                    foreach (array_keys($row) as $key) {
                        $out .= '<th style="padding-right: 1em;">' . $this->getFieldTitle($key) . '</th>';
                    }
                    $out .= '</tr>';
                }

                $out .= '<tr style="border: 1px grey solid; border-collapse: collapse;">';
                foreach ($row as $field) {
                    $out .= '<td style="padding-right: 1em;">' . $field . '</td>';
                }
                $out .= '</tr>';

                if (++$i == $limit) {
                    break;
                }
            }
            $out = '<table style="border: 1px grey solid; border-collapse: collapse;">' . $out . '</table>';

            $authCode = GeneralUtility::stdAuthCode($this->_getCleanProperties());
            $uriXml = UriBuilder::buildFrontendUriFromTcA('RecipientList', 'export', ['uidRecipientList' => $this->getUid(), 'authCode' => $authCode, 'format' => 'xml']);
            $uriCsv = UriBuilder::buildFrontendUriFromTcA('RecipientList', 'export', ['uidRecipientList' => $this->getUid(), 'authCode' => $authCode, 'format' => 'csv']);
            $export = ' (<a href="' . $uriXml . '">export XML</a>, <a href="' . $uriCsv . '">export CSV</a>)';

            $out .= '<p><strong>' . $i . '/' . $this->getCount() . '</strong> recipients' . $export . '</p>';
        }

        $out = '<h4>' . $this->getTitle() . '</h4>' . $out;

        return $out;
    }

    /**
     * Returns the fieldname style according to validation
     * Green => special field recognized
     * Red => field which cannot be used within newsletter with 'markers' features
     * normal => field which can be used within newsletter
     *
     * @param string $fieldname
     */
    private function getFieldTitle($fieldname)
    {
        $knownFields = ['email', 'plain_only', 'L', 'sender_email', 'sender_name', 'replyto_email', 'replyto_name'];

        if (in_array($fieldname, $knownFields, true)) {
            return '<span style="color: green;">' . $fieldname . '</span>';
        } elseif (preg_match('/_[0-9]+$/', $fieldname)) {
            return '<span style="color: red;">' . $fieldname . '</span>';
        }

        return $fieldname;
    }
}
