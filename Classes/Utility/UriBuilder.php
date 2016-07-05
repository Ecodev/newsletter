<?php

namespace Ecodev\Newsletter\Utility;

/**
 * Front end URI builder
 */
abstract class UriBuilder
{
    const EXTENSION_NAME = 'newsletter';
    const PLUGIN_NAME = 'p';

    /**
     * UriBuilders indexed by PID
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder[]
     */
    private static $uriBuilder = [];

    private static function getUriBuilder($currentPid)
    {
        if (!isset(self::$uriBuilder[$currentPid])) {
            $builder = self::createUriBuilder($currentPid);
            self::$uriBuilder[$currentPid] = $builder;
        }

        return self::$uriBuilder[$currentPid];
    }

    /**
     * Build an uriBuilder that can be used from any context (backend, frontend, TCA) to generate frontend URI
     * @param int $currentPid
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    private static function createUriBuilder($currentPid)
    {
        // If we are in Backend we need to simulate minimal TSFE
        if (!isset($GLOBALS['TSFE']) || !($GLOBALS['TSFE'] instanceof \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController)) {
            if (!is_object($GLOBALS['TT'])) {
                $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\TimeTracker();
                $GLOBALS['TT']->start();
            }

            $TSFE = @\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::class, $GLOBALS['TYPO3_CONF_VARS'], $currentPid, '0', 1);

            $GLOBALS['TSFE'] = $TSFE;
            $GLOBALS['TSFE']->initFEuser();
            $GLOBALS['TSFE']->fetch_the_id();
            $GLOBALS['TSFE']->getPageAndRootline();
            $GLOBALS['TSFE']->initTemplate();
            $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
            $GLOBALS['TSFE']->forceTemplateParsing = 1;
            $GLOBALS['TSFE']->getConfigArray();
        }

        // If extbase is not boostrapped yet, we must do it before building uriBuilder (when used from TCA)
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof \TYPO3\CMS\Extbase\Core\Bootstrap)) {
            $extbaseBootstrap = $objectManager->get(\TYPO3\CMS\Extbase\Core\Bootstrap::class);
            $extbaseBootstrap->initialize(['extensionName' => self::EXTENSION_NAME, 'pluginName' => self::PLUGIN_NAME]);
        }

        return $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
    }

    /**
     * Returns a frontend URI independently of current context, with or without extbase, and with or without TSFE
     * @param int $currentPid
     * @param string $controllerName
     * @param string $actionName
     * @param array $arguments
     * @return string absolute URI
     */
    public static function buildFrontendUri($currentPid, $controllerName, $actionName, array $arguments = [])
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $extensionService = $objectManager->get(\TYPO3\CMS\Extbase\Service\ExtensionService::class);
        $pluginNamespace = $extensionService->getPluginNamespace(self::EXTENSION_NAME, self::PLUGIN_NAME);

        // Prepare arguments
        $arguments['action'] = $actionName;
        $arguments['controller'] = $controllerName;
        $namespacedArguments = [$pluginNamespace => $arguments];

        // Configure Uri
        $uriBuilder = self::getUriBuilder($currentPid);
        $uriBuilder->reset()
                ->setUseCacheHash(false)
                ->setCreateAbsoluteUri(true)
                ->setArguments($namespacedArguments)
                ->setTargetPageType(1342671779);

        $uri = $uriBuilder->buildFrontendUri();

        // For some reason the core set the backPath to something while building URI,
        // but it will somehow break the RecipientList editing in backend, so we unset it here
        // This was at least since TYPO3 7.6, maybe earlier, but not on TYPO3 8.0 anymore
        $pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $pageRenderer->backPath = null;

        return $uri;
    }
}
