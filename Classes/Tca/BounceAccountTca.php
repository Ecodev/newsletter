<?php

namespace Ecodev\Newsletter\Tca;

use Ecodev\Newsletter\Tools;

/**
 * Handle bounced account encryption
 */
class BounceAccountTca
{
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
}
