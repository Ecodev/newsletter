<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use Ecodev\Newsletter\Exception;
use TYPO3;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * The Ext Direct request builder
 */
class RequestBuilder implements TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @inject
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * Injects the ObjectManager
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Injects the ConfigurationManager
     *
     * @param ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Builds an Ext Direct request
     *
     * @return Request The built request
     */
    public function build()
    {
        $postArguments = $_POST;
        if (isset($postArguments['extAction'])) {
            throw new Exception('Form Post Request building is not yet implemented.', 1281379502);
        }
        $request = $this->buildJsonRequest();

        return $request;
    }

    /**
     * Builds a Json Ext Direct request by reading the transaction data from
     * standard input.
     *
     * @return Request The Ext Direct request object
     */
    protected function buildJsonRequest()
    {
        $transactionDatas = file_get_contents('php://input');

        if (($transactionDatas = json_decode($transactionDatas)) === null) {
            throw new Exception('The request is not a valid Ext Direct request', 1268490738);
        }

        if (!is_array($transactionDatas)) {
            $transactionDatas = [$transactionDatas];
        }

        $request = $this->objectManager->get(Request::class);
        foreach ($transactionDatas as $transactionData) {
            $request->createAndAddTransaction(
                $transactionData->action, $transactionData->method, is_array($transactionData->data) ? $transactionData->data : [], $transactionData->tid
            );
        }

        return $request;
    }
}
