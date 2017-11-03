<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use Ecodev\Newsletter\ExtDirectRequest;
use Exception;
use TYPO3\CMS\Extbase\Mvc\Dispatcher;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * The Ext Direct request handler
 */
class RequestHandler implements \TYPO3\CMS\Extbase\Mvc\RequestHandlerInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Dispatcher
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
     * @param ObjectManagerInterface $objectManager A reference to the object factory
     * @param Dispatcher $dispatcher The request dispatcher
     * @param Ecodev\Newsletter\MVC\ExtDirect\RequestBuilder $requestBuilder
     */
    public function __construct(
        ObjectManagerInterface $objectManager, Dispatcher $dispatcher, RequestBuilder $requestBuilder)
    {
        $this->objectManager = $objectManager;
        $this->dispatcher = $dispatcher;
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function injectDispatcher(Dispatcher $dispatcher)
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
        return isset($_GET[ExtDirectRequest::class]);
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
        $response = $this->objectManager->get(Response::class);
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
