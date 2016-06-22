<?php

namespace Ecodev\Newsletter\Utility;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Front end URI builder
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
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

        return $uri;
    }
}
