<?php

namespace Ecodev\Newsletter\MVC\ExtDirect;

use Ecodev\Newsletter\Tools;
use ReflectionException;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

/**
 * A Service that provides the Ext.Direct Api
 */
class Api
{
    /**
     * @var ReflectionService
     */
    protected $reflectionService;

    /**
     * @var array
     */
    protected $frameworkConfiguration;

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @param ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->frameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
    }

    /**
     * Injects the reflection service
     *
     * @param ReflectionService $reflectionService
     */
    public function injectReflectionService(ReflectionService $reflectionService)
    {
        $this->reflectionService = $reflectionService;
    }

    /**
     * Creates the remote api based on the module/plugin configuration using the extbase
     * reflection features.
     *
     * @param string $routeUrl
     * @param string $namespace
     *
     * @return array
     */
    public function createApi($routeUrl, $namespace)
    {
        $api = [];
        $api['url'] = $routeUrl;
        $api['type'] = 'remoting';
        $api['namespace'] = $namespace;
        $api['actions'] = [];

        if (empty($this->frameworkConfiguration['controllerConfiguration'])) {
            // @todo improve me! Hack for fetching API of newsletter the hard way!
            // It looks $this->frameworkConfiguration['controllerConfiguration'] is empty as of TYPO3 6.1. Bug or feature?
            $this->frameworkConfiguration['controllerConfiguration'] = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['Newsletter']['modules']['web_NewsletterTxNewsletterM1']['controllers'];
        }

        foreach ($this->frameworkConfiguration['controllerConfiguration'] as $controllerName => $allowedControllerActions) {
            $unstrippedControllerName = $controllerName . 'Controller';
            $controllerObjectName = 'Ecodev\\Newsletter\\Controller\\' . $unstrippedControllerName;
            $controllerActions = [];
            foreach ($allowedControllerActions['actions'] as $actionName) {
                $unstrippedActionName = $actionName . 'Action';
                try {
                    $actionParameters = $this->reflectionService->getMethodParameters($controllerObjectName, $unstrippedActionName);
                    $controllerActions[] = [
                        'len' => count($actionParameters),
                        'name' => $unstrippedActionName,
                    ];
                } catch (ReflectionException $re) {
                    if ($unstrippedActionName !== 'extObjAction') {
                        Tools::getLogger(__CLASS__)->critical('You have a not existing action (' . $controllerObjectName . '::' . $unstrippedActionName . ') in your module/plugin configuration. This action will not be available for Ext.Direct remote execution.');
                    }
                }
            }
            $api['actions'][$unstrippedControllerName] = $controllerActions;
        }

        return $api;
    }
}
