<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use TYPO3\CMS\Extbase\Mvc\Web\Response;

/* *
 * This script belongs to the FLOW3 package "ExtJS".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A Ext Direct specific response implementation with raw content for json encodable results
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class TransactionResponse extends \TYPO3\CMS\Extbase\Mvc\Web\Response
{
    /**
     * The Ext Direct result that will be JSON encoded
     *
     * @var mixed
     */
    protected $result;

    /**
     * The Ext Direct success code. Defaults to TRUE.
     *
     * @var bool
     */
    protected $success = true;

    /**
     * Setter for the transaction result.
     *
     * @param mixed $result The result of the called action
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Sette for success.
     *
     * @param bool $success The success of the called action
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * Returns the result of the transaction.
     *
     * @return mixed The result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Returns the state (success/fail) of the transaction.
     *
     * @return bool The success
     */
    public function getSuccess()
    {
        return $this->success;
    }
}
