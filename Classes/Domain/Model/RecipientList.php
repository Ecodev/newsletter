<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * RecipientList
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class Tx_Newsletter_Domain_Model_RecipientList extends Tx_Extbase_DomainObject_AbstractEntity
{

    /**
     * title
     *
     * @var string $title
     */
    protected $title;

    /**
     * plainOnly
     *
     * @var boolean $plainOnly
     */
    protected $plainOnly;

    /**
     * lang
     *
     * @var string $lang
     */
    protected $lang;

    /**
     * type
     *
     * @var string $type
     */
    protected $type;

    /**
     * Setter for title
     *
     * @param string $title title
     * @return void
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
     * @param boolean $plainOnly plainOnly
     * @return void
     */
    public function setPlainOnly($plainOnly)
    {
        $this->plainOnly = $plainOnly;
    }

    /**
     * Getter for plainOnly
     *
     * @return boolean plainOnly
     */
    public function getPlainOnly()
    {
        return $this->plainOnly;
    }

    /**
     * Returns the state of plainOnly
     *
     * @return boolean the state of plainOnly
     */
    public function isPlainOnly()
    {
        return $this->getPlainOnly();
    }

    /**
     * Setter for lang
     *
     * @param string $lang lang
     * @return void
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Getter for lang
     *
     * @return string lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Setter for type
     *
     * @param string $type type
     * @return void
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
     * Array or mysql result containing raw data for recipient list. Kinf of cache in memory.
     * @var array
     */
    var $data = null;

    /**
     * Initializing method to prepare for reading recipients.
     *
     * @return    void
     */
    abstract public function init();

    /**
     * Fetch one receiver record from the newsletter target.
     * The record MUST contain an "email"-field. Without this one this mailtarget is useless.
     * For compatibility with various subscription systems, the record can contain "tableName"-field.
     *
     * @abstract
     * @return   array      Assoc array with fields for the receiver
     */
    abstract public function getRecipient();

    /**
     * Get the number of receivers in this newsletter target
     *
     * @abstract
     * @return   integer      Numbers of receivers.
     */
    abstract public function getCount();

    /**
     * Get error text if the fetching of the newsletter target has somehow failed.
     *
     * @abstract
     * @return   string      Error text or empty string.
     */
    abstract public function getError();

    /**
     * Here you can define an action when an address bounces. This can either be database operations such as a deletion.
     * For external data-sources, you might consider collecting the addresses for later removal from the foreign system.
     * The Tx_Newsletter_Domain_Model_RecipientList_Sql implements a sensible default. "tableName" should also be included
     * for compatibility reasons.
     *
     * @param string $email the email address of the recipient
     * @param integer $bounceLevel Level of bounce, @see Tx_Newsletter_BounceHandler for possible values
     * @return boolean Status of the success of the removal.
     */
    function registerBounce($email, $bounceLevel)
    {
        return false;
    }

    /**
     * Here you can implement some action to take whenever the user has opened the mail via beenthere.php
     *
     * @param string $email the email address of the recipient (who opened the mail)
     * @return	void
     */
    function registerOpen($email)
    {

    }

    /**
     * Here you can implement some action to take whenever the user has clicked a link via click.php
     *
     * @param string $email the email address of the recipient
     * @return	void
     */
    function registerClick($email)
    {

    }

    /**
     * Return HTML code showing an extract of recipients (first X recipients)
     */
    public function getExtract($limit = 30)
    {
        if ($this->getError()) {
            $out = "Error: " . $this->getError();
        } else {
            $i = 0;
            while ($row = $this->getRecipient()) {
                // Dump formatted table header
                if ($i == 0) {
                    $out .= '<tr>';
                    foreach (array_keys($row) as $key) {
                        $out .= '<th style="padding-right: 1em;">' . $this->getFieldTitle($key) . "</th>";
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

            $authCode = t3lib_div::stdAuthCode($this->_getCleanProperties());
            $uriXml = Tx_Newsletter_Tools::buildFrontendUri('export', array('uidRecipientList' => $this->getUid(), 'authCode' => $authCode, 'format' => 'xml'), 'RecipientList');
            $uriCsv = Tx_Newsletter_Tools::buildFrontendUri('export', array('uidRecipientList' => $this->getUid(), 'authCode' => $authCode, 'format' => 'csv'), 'RecipientList');

            $out .= '<p><strong>' . $i . '/' . $this->getCount() . '</strong> recipients
			(<a href="' . $uriXml . "\">export XML</a>, "
                    . '<a href="' . $uriCsv . "\">export CSV</a>"
                    . ')</p>';
        }

        $out = '<h4>' . $this->getTitle() . '</h4>' . $out;
        return $out;
    }

    /**
     * Returns the fieldname style according to validation
     * Green => special field recognized
     * Red => field which cannot be used within newsletter with 'markers' features
     * normal => field which can be used within newsletter
     * @param string $fieldname
     */
    private static function getFieldTitle($fieldname)
    {
        switch ($fieldname) {
            case 'email':
            case 'plain_only':
            case 'authCode':
            case 'uid':
            case 'tableName':
            case 'L':
                return '<span style="color: green;">' . $fieldname . '</span>';

            default:
                if (preg_match('/_[0-9]+$/', $fieldname)) {
                    return '<span style="color: red;">' . $fieldname . '</span>';
                } else {
                    return $fieldname;
                }
        }
    }

}
