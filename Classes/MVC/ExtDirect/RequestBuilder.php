<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use Ecodev\Newsletter\Exception as EcodevNewsletterException;
use TYPO3;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

/**
 * The Ext Direct request builder
 */
class RequestBuilder implements TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @inject
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @inject
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     */
    protected $configurationManager;

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
     * Injects the ConfigurationManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Builds an Ext Direct request
     *
     * @return Ecodev\Newsletter\MVC\ExtDirect\Request The built request
     */
    public function build()
    {
        $postArguments = $_POST;
        if (isset($postArguments['extAction'])) {
            throw new EcodevNewsletterException('Form Post Request building is not yet implemented.', 1281379502);
            $request = $this->buildFormPostRequest($postArguments);
        } else {
            $request = $this->buildJsonRequest();
        }

        return $request;
    }

    /**
     * Builds a Json Ext Direct request by reading the transaction data from
     * standard input.
     *
     * @throws \Exception
     * @return Ecodev\Newsletter\MVC\ExtDirect\Request The Ext Direct request object
     */
    protected function buildJsonRequest()
    {
        $transactionDatas = file_get_contents('php://input');

        if (($transactionDatas = json_decode($transactionDatas)) === null) {
            throw new \Exception('The request is not a valid Ext Direct request', 1268490738);
        }

        if (!is_array($transactionDatas)) {
            $transactionDatas = [$transactionDatas];
        }

        $request = $this->objectManager->get(\Ecodev\Newsletter\MVC\ExtDirect\Request::class);
        foreach ($transactionDatas as $transactionData) {
            $request->createAndAddTransaction(
                    $transactionData->action, $transactionData->method, is_array($transactionData->data) ? $transactionData->data : [], $transactionData->tid
            );
        }

        return $request;
    }

    /**
     * Builds a Form Post Ext Direct Request
     *
     * @return Ecodev\Newsletter\MVC\ExtDirect\Request The Ext Direct request object
     * @todo Well... make it work, eh?
     */
    protected function buildFormPostRequest()
    {
        $directRequest->setFormPost(true);
        $directRequest->setFileUpload($request->getArgument('extUpload') === 'true');

        $packageKey = $request->getArgument('packageKey');
        $subpackageKey = $request->hasArgument('subpackageKey') ? $request->getArgument('subpackageKey') : '';

        $directRequest->addTransaction(
                $request->getArgument('extAction'), $request->getArgument('extMethod'), null, $request->getArgument('extTID'), $packageKey, $subpackageKey
        );
    }
}
