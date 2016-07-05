<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use Exception;
use TYPO3\CMS\Extbase\Mvc\Dispatcher;

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
 * The Ext Direct request handler
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class RequestHandler implements \TYPO3\CMS\Extbase\Mvc\RequestHandlerInterface
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Ecodev\Newsletter\MVC\ExtDirect\RequestBuilder
     */
    protected $requestBuilder;

    /**
     * Whether to expose exception information in an ExtDirect response
     * @var bool
     */
    protected $exposeExceptionInformation = true;

    /**
     * Constructs the Ext Direct Request Handler
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager A reference to the object factory
     * @param \TYPO3\CMS\Extbase\Mvc\Dispatcher $dispatcher The request dispatcher
     * @param Ecodev\Newsletter\MVC\ExtDirect\RequestBuilder $requestBuilder
     */
    public function __construct(
    \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager, \TYPO3\CMS\Extbase\Mvc\Dispatcher $dispatcher, RequestBuilder $requestBuilder)
    {
        $this->objectManager = $objectManager;
        $this->dispatcher = $dispatcher;
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\Dispatcher $dispatcher
     */
    public function injectDispatcher(\TYPO3\CMS\Extbase\Mvc\Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Ecodev\Newsletter\MVC\ExtDirect\RequestBuilder $requestBuilder
     */
    public function injectRequestBuilder(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * Handles a raw Ext Direct request and sends the respsonse.
     */
    public function handleRequest()
    {
        $extDirectRequest = $this->requestBuilder->build();

        $results = [];
        foreach ($extDirectRequest->getTransactions() as $transaction) {
            $transactionRequest = $transaction->buildRequest();
            $transactionResponse = $transaction->buildResponse();

            try {
                $this->dispatcher->dispatch($transactionRequest, $transactionResponse);
                $results[] = [
                    'type' => 'rpc',
                    'tid' => $transaction->getTid(),
                    'action' => $transaction->getAction(),
                    'method' => $transaction->getMethod(),
                    'result' => $transactionResponse->getResult(),
                ];
            } catch (Exception $exception) {
                $exceptionMessage = $this->exposeExceptionInformation ? $exception->getMessage() : 'An internal error occured';
                $exceptionWhere = $this->exposeExceptionInformation ? $exception->getTraceAsString() : '';
                $results[] = [
                    'type' => 'exception',
                    'tid' => $transaction->getTid(),
                    'message' => $exceptionMessage,
                    'where' => $exceptionWhere,
                ];
            }
        }

        return $this->sendResponse($results, $extDirectRequest);
    }

    /**
     * Checks if the request handler can handle the current request.
     *
     * @return bool TRUE if it can handle the request, otherwise FALSE
     */
    public function canHandleRequest()
    {
        return isset($_GET[\Ecodev\Newsletter\ExtDirectRequest::class]);
    }

    /**
     * Returns the priority - how eager the handler is to actually handle the
     * request.
     *
     * @return int The priority of the request handler
     */
    public function getPriority()
    {
        return 200;
    }

    /**
     * Sends the response
     *
     * @param array $results The collected results from the transaction requests
     * @param Ecodev\Newsletter\MVC\ExtDirect\Request $extDirectRequest
     */
    protected function sendResponse(array $results, Request $extDirectRequest)
    {
        $response = $this->objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Response::class);
        $jsonResponse = json_encode(count($results) === 1 ? $results[0] : $results);
        if ($extDirectRequest->isFormPost() && $extDirectRequest->isFileUpload()) {
            $response->setHeader('Content-Type', 'text/html');
            $response->setContent('<html><body><textarea>' . $jsonResponse . '</textarea></body></html>');
        } else {
            $response->setHeader('Content-Type', 'application/json');
            $response->setContent($jsonResponse);
        }

        return $response;
    }
}
