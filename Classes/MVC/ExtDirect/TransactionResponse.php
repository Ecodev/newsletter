<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use TYPO3\CMS\Extbase\Mvc\Web\Response;

/**
 * A Ext Direct specific response implementation with raw content for json encodable results
 *
 * @scope prototype
 */
class TransactionResponse extends Response
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
