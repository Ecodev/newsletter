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
    /**
     * UriBuilder
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    private static $uriBuilder = null;

    /**
     * Build an uriBuilder that can be used from any context (backend, frontend, TCA) to generate frontend URI
     * @param string $extensionName
     * @param string $pluginName
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    private static function buildUriBuilder($extensionName, $pluginName)
    {

        // If we are in Backend we need to simulate minimal TSFE
        if (!isset($GLOBALS['TSFE']) || !($GLOBALS['TSFE'] instanceof \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController)) {
            if (!is_object($GLOBALS['TT'])) {
                $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\TimeTracker();
                $GLOBALS['TT']->start();
            }
            $pid = 0;
            $TSFE = @\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $pid, '0', 1);

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
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof \TYPO3\CMS\Extbase\Core\Bootstrap)) {
            $extbaseBootstrap = $objectManager->get('TYPO3\\CMS\\Extbase\\Core\\Bootstrap');
            $extbaseBootstrap->initialize(array('extensionName' => $extensionName, 'pluginName' => $pluginName));
        }

        return $objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
    }

    /**
     * Returns a frontend URI independently of current context, with or without extbase, and with or without TSFE
     * @param string $actionName
     * @param array $controllerArguments
     * @param string $controllerName
     * @param string $extensionName
     * @param string $pluginName
     * @param array $otherArguments
     * @return string absolute URI
     */
    public static function buildFrontendUri($actionName, array $controllerArguments, $controllerName, $extensionName = 'newsletter', $pluginName = 'p', array $otherArguments = null)
    {
        if (!self::$uriBuilder) {
            self::$uriBuilder = self::buildUriBuilder($extensionName, $pluginName);
        }
        $controllerArguments['action'] = $actionName;
        $controllerArguments['controller'] = $controllerName;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $extensionService = $objectManager->get('TYPO3\\CMS\\Extbase\\Service\\ExtensionService');
        $pluginNamespace = $extensionService->getPluginNamespace($extensionName, $pluginName);

        if (!isset($otherArguments) || is_null($otherArguments)) {
            $otherArguments = array();
        }

        $arguments = $otherArguments;
        $arguments[$pluginNamespace] = $controllerArguments;
        self::$uriBuilder->reset()
            ->setUseCacheHash(false)
            ->setCreateAbsoluteUri(true)
            ->setArguments($arguments)
            ->setTargetPageType(1342671779);

        return self::$uriBuilder->buildFrontendUri();
    }
}
