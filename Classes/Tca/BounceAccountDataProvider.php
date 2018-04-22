<?php

namespace Ecodev\Newsletter\Tca;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Handle bounced account on-the-fly decryption for TYPO3 7.6
 * TYPO3 6.2 versions are done via a hook in BounceAccountTca class
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
                $result['databaseRow'][$field] = BounceAccountTca::getDecryptedFieldValue($field, $result['databaseRow'][$field]);
            }
        }

        return $result;
    }
}
