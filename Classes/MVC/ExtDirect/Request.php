<?php

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
 * An Ext Direct request
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class Tx_Newsletter_MVC_ExtDirect_Request
{

    /**
     * @inject
     * @var Tx_Extbase_Object_ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * The transactions inside this request
     *
     * @var array
     */
    protected $transactions = array();

    /**
     * True if this request is a form post
     *
     * @var boolean
     */
    protected $formPost = FALSE;

    /**
     * True if this request is containing a file upload
     *
     * @var boolean
     */
    protected $fileUpload = FALSE;

    /**
     * Injects the ObjectManager
     *
     * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
     * @return void
     */
    public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Creates an Ext Direct Transaction and adds it to the request instance.
     *
     * @param string $action The "action" – the "controller object name" in FLOW3 terms
     * @param string $method The "method" – the "action name" in FLOW3 terms
     * @param array $data Numeric array of arguments which are eventually passed to the FLOW3 action method
     * @param mixed $tid The ExtDirect transaction id
     * @return void
     */
    public function createAndAddTransaction($action, $method, array $data, $tid)
    {
        $transaction = $this->objectManager->create('Tx_Newsletter_MVC_ExtDirect_Transaction', $this, $action, $method, $data, $tid);
        $this->transactions[] = $transaction;
    }

    /**
     * Getter for transactions.
     *
     * @return array
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Whether this request represents a form post or not.
     *
     * @return boolean
     */
    public function isFormPost()
    {
        return $this->formPost;
    }

    /**
     * Marks this request as representing a form post or not.
     *
     * @param boolean $formPost
     * @return void
     */
    public function setFormPost($formPost)
    {
        $this->formPost = $formPost;
    }

    /**
     * Whether this request represents a file upload or not.
     *
     * @return boolean
     */
    public function isFileUpload()
    {
        return $this->fileUpload;
    }

    /**
     * Marks this request as representing a file upload or not.
     *
     * @param boolean $fileUpload
     * @return void
     */
    public function setFileUpload($fileUpload)
    {
        $this->fileUpload = $fileUpload ? TRUE : FALSE;
    }

}
