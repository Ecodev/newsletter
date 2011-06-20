<?php
declare(ENCODING = 'utf-8');

/*                                                                        *
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
class Tx_MvcExtjs_MVC_ExtDirect_RequestHandler implements Tx_Extbase_MVC_RequestHandlerInterface {

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var Tx_Extbase_MVC_Dispatcher
	 */
	protected $dispatcher;

	/**
	 * @var Tx_Extbase_MVC_Controller_FlashMessages
	 */
	protected $flashMessages;

	/**
	 * @var Tx_MvcExtjs_MVC_ExtDirect_RequestBuilder
	 */
	protected $requestBuilder;

	/**
	 * Whether to expose exception information in an ExtDirect response
	 * @var boolean
	 */
	protected $exposeExceptionInformation = TRUE;

	/**
	 * Constructs the Ext Direct Request Handler
	 *
	 * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager A reference to the object factory
	 * @param Tx_Extbase_MVC_Dispatcher $dispatcher The request dispatcher
	 * @param Tx_MvcExtjs_MVC_ExtDirect_RequestBuilder $requestBuilder
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function __construct(
			Tx_Extbase_Object_ObjectManagerInterface $objectManager,
			Tx_Extbase_MVC_Dispatcher $dispatcher,
			Tx_MvcExtjs_MVC_ExtDirect_RequestBuilder $requestBuilder) {
		$this->objectManager = $objectManager;
		$this->dispatcher = $dispatcher;
		$this->requestBuilder = $requestBuilder;
	}

	/**
	 * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * @param Tx_Extbase_MVC_Controller_FlashMessages $objectManager
	 * @return void
	 */
	public function injectFlashMessages(Tx_Extbase_MVC_Controller_FlashMessages $flashMessages) {
		$this->flashMessages = $flashMessages;
	}

	/**
	 * @param Tx_Extbase_MVC_Dispatcher $dispatcher
	 * @return void
	 */
	public function injectDispatcher(Tx_Extbase_MVC_Dispatcher $dispatcher) {
		$this->dispatcher = $dispatcher;
	}

	/**
	 * @param Tx_MvcExtjs_MVC_ExtDirect_RequestBuilder $requestBuilder
	 * @return void
	 */
	public function injectRequestBuilder(Tx_MvcExtjs_MVC_ExtDirect_RequestBuilder $requestBuilder) {
		$this->requestBuilder = $requestBuilder;
	}

	/**
	 * Handles a raw Ext Direct request and sends the respsonse.
	 *
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function handleRequest() {
		$extDirectRequest = $this->requestBuilder->build();

		$results = array();
		foreach ($extDirectRequest->getTransactions() as $transaction) {
			$transactionRequest = $transaction->buildRequest();
			$transactionResponse = $transaction->buildResponse();

			try {
				$this->dispatcher->dispatch($transactionRequest, $transactionResponse);
				$results[] = array(
					'type' => 'rpc',
					'tid' => $transaction->getTid(),
					'action' => $transaction->getAction(),
					'method' => $transaction->getMethod(),
					'result' => $transactionResponse->getResult()
				);
			} catch (Exception $exception) {
				$exceptionMessage = $this->exposeExceptionInformation ? $exception->getMessage() : 'An internal error occured';
				$exceptionWhere = $this->exposeExceptionInformation ? $exception->getTraceAsString() : '';
				$results[] = array(
					'type' => 'exception',
					'tid' => $transaction->getTid(),
					'message' => $exceptionMessage,
					'where' => $exceptionWhere
				);
			}
		}
		return $this->sendResponse($results, $extDirectRequest);
	}

	/**
	 * Checks if the request handler can handle the current request.
	 *
	 * @return boolean TRUE if it can handle the request, otherwise FALSE
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function canHandleRequest() {
		return isset($_GET['Tx_MvcExtjs_ExtDirectRequest']);
	}

	/**
	 * Returns the priority - how eager the handler is to actually handle the
	 * request.
	 *
	 * @return integer The priority of the request handler
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getPriority() {
		return 200;
	}

	/**
	 * Sends the response
	 *
	 * @param array $results The collected results from the transaction requests
	 * @param Tx_MvcExtjs_MVC_ExtDirect_Request $extDirectRequest
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	protected function sendResponse(array $results, Tx_MvcExtjs_MVC_ExtDirect_Request $extDirectRequest) {
		$response = $this->objectManager->create('Tx_Extbase_MVC_Web_Response');
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
?>