<?php

namespace Ecodev\Newsletter\Tca;

/**
 * Handle bounced account encryption
 */
class BounceAccountTca
{
    /**
     * Decrypt values from DB (on TYPO3 6.2)
     */
    public function getSingleField_preProcess($table, $field, &$row, $altName, $palette, $extra, $pal, &$pObj)
    {
        $encryptedFields = ['password', 'config'];
        if ($table == 'tx_newsletter_domain_model_bounceaccount' && in_array($field, $encryptedFields)) {
            $row[$field] = self::getDecryptedFieldValue($field, $row[$field]);
        }
    }

    /**
     * Encrypts the field value
     * @param string $value The field value to be evaluated.
     * @param string $isIn The "isIn" value of the field configuration from TCA
     * @param bool $set defining if the value is written to the database or not.
     * @return string
     */
    public function evaluateFieldValue($value, $isIn, &$set)
    {
        return \Ecodev\Newsletter\Tools::encrypt($value);
    }

    /**
     * Returns the decrypted field value if set.
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
            $value = \Ecodev\Newsletter\Tools::decrypt($value);
        }

        return $value;
    }
}
