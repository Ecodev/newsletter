<?php

namespace Ecodev\Newsletter\Tca;

use Ecodev\Newsletter\Tools;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Handle bounced account on-the-fly decryption for TYPO3 7.6+
 */
class BounceAccountDataProvider implements FormDataProviderInterface
{
    /**
     * Decrypt values from DB (on TYPO3 7.6)
     *
     * @param array $result Initialized result array
     *
     * @return array Result filled with decrypted values
     */
    public function addData(array $result)
    {
        if ($result['tableName'] == 'tx_newsletter_domain_model_bounceaccount') {
            $encryptedFields = ['password', 'config'];
            foreach ($encryptedFields as $field) {
                $result['databaseRow'][$field] = $this->getDecryptedFieldValue($field, $result['databaseRow'][$field]);
            }
        }

        return $result;
    }

    /**
     * Returns the decrypted field value if set.
     *
     * @param mixed $field
     * @param mixed $value
     *
     * @return string
     */
    private function getDecryptedFieldValue($field, $value)
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
