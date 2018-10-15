<?php

namespace Ecodev\Newsletter\Utility;

use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Front end URI builder
 */
abstract class UriBuilder
{
    const EXTENSION_NAME = 'newsletter';
    const PLUGIN_NAME = 'p';
    const PAGE_TYPE = 1342671779;

    /**
     * UriBuilders indexed by PID
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder[]
     */
    private static $uriBuilder = [];

    /**
     * Cache of URI to avoid hitting RealURL when possible
     *
     * @var array
     */
    private static $uriCache = [];

    /**
     * @var string plugin namespace for arguments
     */
    private static $namespace;

    /**
     * @param int $currentPid
     *
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    private static function getUriBuilder($currentPid)
    {
        if (!isset(self::$uriBuilder[$currentPid])) {
            $builder = self::createUriBuilder($currentPid);
            self::$uriBuilder[$currentPid] = $builder;
        }

        return self::$uriBuilder[$currentPid];
    }

    /**
     * Build an uriBuilder that can be used from any context (backend, frontend) to generate frontend URI
     *
     * @param int $currentPid
     *
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    private static function createUriBuilder($currentPid)
    {
        // If we are in Backend we need to simulate minimal TSFE
        if (!isset($GLOBALS['TSFE']) || !($GLOBALS['TSFE'] instanceof TypoScriptFrontendController)) {
            if (!is_object($GLOBALS['TT'])) {
                $GLOBALS['TT'] = new TimeTracker();
                $GLOBALS['TT']->start();
            }

            $TSFE = @GeneralUtility::makeInstance(TypoScriptFrontendController::class, $GLOBALS['TYPO3_CONF_VARS'], $currentPid, '0', 1);

            $GLOBALS['TSFE'] = $TSFE;
            $GLOBALS['TSFE']->initFEuser();
            $GLOBALS['TSFE']->fetch_the_id();
            $GLOBALS['TSFE']->getPageAndRootline();
            $GLOBALS['TSFE']->initTemplate();
            $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
            $GLOBALS['TSFE']->forceTemplateParsing = 1;
            $GLOBALS['TSFE']->getConfigArray();
        }

        // If extbase is not boostrapped yet, we must do it before building uriBuilder (when used from scheduler CLI)
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof Bootstrap)) {
            $extbaseBootstrap = $objectManager->get(Bootstrap::class);
            $extbaseBootstrap->initialize(['extensionName' => self::EXTENSION_NAME, 'pluginName' => self::PLUGIN_NAME]);
        }

        return $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
    }

    /**
     * Return an array of namespaced arguments
     *
     * @param string $controllerName
     * @param string $actionName
     * @param array $arguments
     *
     * @return array
     */
    private static function getNamespacedArguments($controllerName, $actionName, array $arguments)
    {
        $pluginNamespace = self::getNamespace();

        // Prepare arguments
        $arguments['action'] = $actionName;
        $arguments['controller'] = $controllerName;
        $namespacedArguments = [$pluginNamespace => $arguments];

        return $namespacedArguments;
    }

    /**
     * Returns an ugly frontend URI from TCA context
     *
     * @param string $controllerName
     * @param string $actionName
     * @param array $arguments
     *
     * @return string absolute URI
     */
    public static function buildFrontendUriFromTca($controllerName, $actionName, array $arguments = [])
    {
        $namespacedArguments = self::getNamespacedArguments($controllerName, $actionName, $arguments);
        $namespacedArguments['type'] = self::PAGE_TYPE;
        $uri = '/?' . http_build_query($namespacedArguments);

        return $uri;
    }

    /**
     * Returns a frontend URI independently of current context (backend or frontend)
     *
     * @param int $currentPid
     * @param string $controllerName
     * @param string $actionName
     * @param array $arguments
     *
     * @return string absolute URI
     */
    public static function buildFrontendUri($currentPid, $controllerName, $actionName, array $arguments = [])
    {
        $argumentsToRestore = array_intersect_key($arguments, array_fill_keys(['c', 'l'], null));
        unset($arguments['c'], $arguments['l']);
        $cacheKey = serialize([$currentPid, $controllerName, $actionName, $arguments]);

        if (array_key_exists($cacheKey, self::$uriCache)) {
            $uri = self::$uriCache[$cacheKey];
        } else {
            $namespacedArguments = self::getNamespacedArguments($controllerName, $actionName, $arguments);

            // Configure Uri
            $uriBuilder = self::getUriBuilder($currentPid);
            $uriBuilder->reset()
                ->setUseCacheHash(false)
                ->setCreateAbsoluteUri(true)
                ->setArguments($namespacedArguments)
                ->setTargetPageType(self::PAGE_TYPE);

            // check if running cli mode
            $environment = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\EnvironmentService');
            if ($environment->isEnvironmentInCliMode()) {
                // create a proper context in cli mode before building the uri. buildFrontendUri will otherwise throw an error.
                $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
                $request = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Request::class);
                $request->setRequestURI(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
                $request->setBaseURI(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
                $uriBuilder = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
                // setCreateAbsoluteUri(true) doesn't work in cli mode since \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') is not filled correctly without http context
                $uriBuilder->setRequest($request)
                    ->setUseCacheHash(false)
                    ->setArguments($namespacedArguments)
                    ->setTargetPageType(self::PAGE_TYPE);                
                
                // prepend TYPO3_REQUEST_HOST to make uri absolute
                $uri = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $uriBuilder->buildFrontendUri();
            } else {
                $uri = $uriBuilder->buildFrontendUri();
            }


            self::$uriCache[$cacheKey] = $uri;
        }

        // Re-append linkAuthCode
        if ($argumentsToRestore) {
            $prefix = strpos($uri, '?') === false ? '?' : '&';
            $uri .= $prefix . http_build_query([self::getNamespace() => $argumentsToRestore]);
        }

        return $uri;
    }

    /**
     * Returns the plugin namespace for arguments
     *
     * @return string
     */
    private static function getNamespace()
    {
        if (!self::$namespace) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $extensionService = $objectManager->get(ExtensionService::class);
            self::$namespace = $extensionService->getPluginNamespace(self::EXTENSION_NAME, self::PLUGIN_NAME);
        }

        return self::$namespace;
    }
}
