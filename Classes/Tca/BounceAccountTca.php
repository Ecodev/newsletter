<?php

namespace Ecodev\Newsletter\Tca;

use Ecodev\Newsletter\Tools;

/**
 * Handle bounced account encryption
 */
class BounceAccountTca
{
    /**
     * Decrypt values from DB (on TYPO3 6.2)
     *
     * @param mixed $table
     * @param mixed $field
     * @param mixed $row
     * @param mixed $altName
     * @param mixed $palette
     * @param mixed $extra
     * @param mixed $pal
     * @param mixed $pObj
     */
    public function getSingleField_preProcess($table, $field, &$row, $altName, $palette, $extra, $pal, &$pObj)
    {
        $encryptedFields = ['password', 'config'];
        if ($table == 'tx_newsletter_domain_model_bounceaccount' && in_array($field, $encryptedFields, true)) {
            $row[$field] = self::getDecryptedFieldValue($field, $row[$field]);
        }
    }

    /**
     * Encrypts the field value
     *
     * @param string $value the field value to be evaluated
     * @param string $isIn The "isIn" value of the field configuration from TCA
     * @param bool $set defining if the value is written to the database or not
     *
     * @return string
     */
    public function evaluateFieldValue($value, $isIn, &$set)
    {
        return Tools::encrypt($value);
    }

    /**
     * Returns the decrypted field value if set.
     *
     * @param mixed $field
     * @param mixed $value
     *
     * @return string
     */
    public static function getDecryptedFieldValue($field, $value)
    {
        $default = @$GLOBALS['TCA']['tx_newsletter_domain_model_bounceaccount']['columns'][$field]['config']['default'];

        // Set the value
        if (empty($value)) {
            if ($default) {
                $value = $default;
            }
        } elseif ($value != $default) {
            $value = Tools::decrypt($value);
        }

        return $value;
    }
}
