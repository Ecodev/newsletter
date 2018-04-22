<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

/**
 * An Ext Direct transaction
 *
 * @scope prototype
 */
class Transaction
{
    /**
     * @inject
     * @var \TYPO3\CMS\Extbase\Reflection\ReflectionService
     */
    protected $reflectionService;

    /**
     * @inject
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * The direct request this transaction belongs to
     *
     * @var Request
     */
    protected $request;

    /**
     * The controller / class to use
     *
     * @var string
     */
    protected $action;

    /**
     * The action / method to execute
     *
     * @var string
     */
    protected $method;

    /**
     * The arguments to be passed to the method
     *
     * @var array
     */
    protected $data;

    /**
     * The transaction ID to associate with this request
     *
     * @var int
     */
    protected $tid;

    /**
     * Constructs the Transaction
     *
     * @param Request $request The direct request this transaction belongs to
     * @param string $action The "action" – the "controller object name" in FLOW3 terms
     * @param string $method The "method" – the "action name" in FLOW3 terms
     * @param array $data Numeric array of arguments which are eventually passed to the FLOW3 action method
     * @param mixed $tid The ExtDirect transaction id
     */
    public function __construct(Request $request, $action, $method, array $data, $tid)
    {
        $this->request = $request;
        $this->action = $action;
        $this->method = $method;
        $this->data = $data;
        $this->tid = $tid;
    }

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Injects the Reflection Service
     *
     * @param ReflectionService $reflectionService
     */
    public function injectReflectionService(ReflectionService $reflectionService)
    {
        $this->reflectionService = $reflectionService;
    }

    /**
     * Build a web request for dispatching this Ext Direct transaction
     *
     * @return Request A web request for this transaction
     */
    public function buildRequest()
    {
        $frameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $request = $this->objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Request::class);
        $request->setControllerObjectName($this->getControllerObjectName());
        $request->setControllerActionName($this->getControllerActionName());
        $request->setPluginName($frameworkConfiguration['pluginName']);
        $request->setFormat('extdirect');
        $request->setArguments($this->getArguments());

        return $request;
    }

    /**
     * Build a response for dispatching this Ext Direct transaction
     *
     * @return TransactionResponse A response for dispatching this transaction
     */
    public function buildResponse()
    {
        return $this->objectManager->get(TransactionResponse::class);
    }

    /**
     * Getter for action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Getter for method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Getter for data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Getter for type
     *
     * @return string The transaction type, currently always "rpc"
     */
    public function getType()
    {
        return 'rpc';
    }

    /**
     * Getter for tid
     *
     * @return int
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Getter for the controller object name
     *
     * @return string
     */
    public function getControllerObjectName()
    {
        return 'Ecodev\\Newsletter\\Controller\\' . $this->action;
    }

    /**
     * Getter for the controller object name
     *
     * @return string
     */
    public function getControllerActionName()
    {
        return str_replace('Action', '', $this->method);
    }

    /**
     * Ext Direct does not provide named arguments by now, so we have
     * to map them by reflecting on the action parameters.
     *
     * @return array The mapped arguments
     */
    public function getArguments()
    {
        if ($this->data === []) {
            return [];
        }

        $arguments = [];

        if (!$this->request->isFormPost()) {
            $parameters = $this->reflectionService->getMethodParameters($this->getControllerObjectName(), $this->getControllerActionName() . 'Action');

            // TODO Add checks for parameters
            foreach ($parameters as $name => $options) {
                $parameterIndex = $options['position'];
                if (isset($this->data[$parameterIndex])) {
                    $arguments[$name] = $this->convertObjectToArray($this->data[$parameterIndex]);
                }
            }
        }

        return $arguments;
    }

    /**
     * Convert an object to an array recursively
     *
     * @param object $object The object to convert
     *
     * @return array The object converted to an array
     */
    protected function convertObjectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}
