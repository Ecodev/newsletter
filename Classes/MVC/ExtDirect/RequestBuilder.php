<?php


namespace Ecodev\Newsletter\MVC\ExtDirect;

use Ecodev\Newsletter\Exception as EcodevNewsletterException;
use Exception as Exception;
use TYPO3;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

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
 * The Ext Direct request builder
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
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
     * @return void
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Injects the ConfigurationManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     * @return void
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
     * @return Ecodev\Newsletter\MVC\ExtDirect\Request The Ext Direct request object
     * @throws Exception
     * @author Christopher Hlubek <hlubek@networkteam.com>
     * @author Robert Lemke <robert@typo3.org>
     */
    protected function buildJsonRequest()
    {
        $transactionDatas = file_get_contents("php://input");

        if (($transactionDatas = json_decode($transactionDatas)) === null) {
            throw new Exception('The request is not a valid Ext Direct request', 1268490738);
        }

        if (!is_array($transactionDatas)) {
            $transactionDatas = array($transactionDatas);
        }

        $request = $this->objectManager->get('Ecodev\\Newsletter\\MVC\\ExtDirect\\Request');
        foreach ($transactionDatas as $transactionData) {
            $request->createAndAddTransaction(
                    $transactionData->action, $transactionData->method, is_array($transactionData->data) ? $transactionData->data : array(), $transactionData->tid
            );
        }

        return $request;
    }

    /**
     * Builds a Form Post Ext Direct Request
     *
     * @return Ecodev\Newsletter\MVC\ExtDirect\Request The Ext Direct request object
     * @author Christopher Hlubek <hlubek@networkteam.com>
     * @author Robert Lemke <robert@typo3.org>
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
