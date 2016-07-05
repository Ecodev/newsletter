<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

/**
 * An Ext Direct request
 *
 * @scope prototype
 */
class Request
{
    /**
     * @inject
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * The transactions inside this request
     *
     * @var array
     */
    protected $transactions = [];

    /**
     * True if this request is a form post
     *
     * @var bool
     */
    protected $formPost = false;

    /**
     * True if this request is containing a file upload
     *
     * @var bool
     */
    protected $fileUpload = false;

    /**
     * Injects the ObjectManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
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
     */
    public function createAndAddTransaction($action, $method, array $data, $tid)
    {
        $transaction = $this->objectManager->get(\Ecodev\Newsletter\MVC\ExtDirect\Transaction::class, $this, $action, $method, $data, $tid);
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
     * @return bool
     */
    public function isFormPost()
    {
        return $this->formPost;
    }

    /**
     * Marks this request as representing a form post or not.
     *
     * @param bool $formPost
     */
    public function setFormPost($formPost)
    {
        $this->formPost = $formPost;
    }

    /**
     * Whether this request represents a file upload or not.
     *
     * @return bool
     */
    public function isFileUpload()
    {
        return $this->fileUpload;
    }

    /**
     * Marks this request as representing a file upload or not.
     *
     * @param bool $fileUpload
     */
    public function setFileUpload($fileUpload)
    {
        $this->fileUpload = $fileUpload ? true : false;
    }
}
