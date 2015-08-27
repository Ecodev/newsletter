<?php

namespace Ecodev\Newsletter\Tca;

/**
 * Handle bounced account encryption
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BounceAccountTca
{
	
	/**
	 * Adds new JavaScript function for evaluation of the TCA fields in backend
	 * @return 	string		JavaScript
	 */
	public function returnFieldJS() 
	{
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
	 * Returns the decrypted password field
	 " @param array $PA Parameter Array
	 * @return string
	 */
	public function passwordField($PA, $fObj)
	{
		$PA['itemFormElValue'] = $this->getDecryptedFieldValue($PA);
		$formField = $fObj->getSingleField_typeInput($PA['table'],$PA['field'],array($PA['field']['uid']),$PA);
		$formField = str_replace('type="text"','type="password"',$formField);
		return $formField;
	}
	
	/**
	 * Returns the decrypted textarea field
	 " @param array $PA Parameter Array
	 * @return string
	 */
	public function textareaField($PA, $fObj)
	{
		$PA['itemFormElValue'] = $this->getDecryptedFieldValue($PA);
		$formField = $fObj->getSingleField_typeText($PA['table'],$PA['field'],array($PA['field']['uid']),$PA);
		return $formField;
	}
	
	/**
	 * Returns the decrypted field value if set.
	 " @param array $PA Parameter Array
	 * @return string
	 */
	protected function getDecryptedFieldValue($PA)
	{
		// Set the value
		$value = $PA['itemFormElValue'];
		if (empty($value)) {
			if (isset($PA['fieldConf']['config']['default'])) {
				$value = $PA['fieldConf']['config']['default'];
			}
		} else if ($value != $PA['fieldConf']['config']['default']) {
			$value = \Ecodev\Newsletter\Tools::decrypt($value);
		}
		return $value;
	}
}