<?php

namespace Ecodev\Newsletter\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Dennis Ahrens <dennis.ahrens@fh-hannover.de>
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
 * View helper which allows
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo">
 * 	<newsletter:includeDirectApi />
 * </newsletter:be.moduleContainer>
 *
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class ExtDirectProviderViewHelper extends AbstractViewHelper
{
    /**
     * @var \Ecodev\Newsletter\MVC\ExtDirect\Api
     */
    protected $apiService;

    /**
     * @see Classes/Core/ViewHelper/\TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper#initializeArguments()
     */
    public function initializeArguments()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->apiService = $objectManager->get('Ecodev\\Newsletter\\MVC\\ExtDirect\\Api');
    }

    /**
     * Generates a Ext.Direct API descriptor and adds it to the pagerenderer.
     * Also calls Ext.Direct.addProvider() on itself (at js side).
     * The remote API is directly useable.
     *
     * @param string $name The name for the javascript variable.
     * @param string $namespace The namespace the variable is placed.
     * @param string $routeUrl You can specify a URL that acts as router.
     */
    public function render($name = 'remoteDescriptor', $namespace = 'Ext.ux.Ecodev.Newsletter.Remote', $routeUrl = null)
    {
        if ($routeUrl === null) {
            $routeUrl = $this->controllerContext->getUriBuilder()->reset()->build() . '&Ecodev\\Newsletter\\ExtDirectRequest=1';
        }

        $api = $this->apiService->createApi($routeUrl, $namespace);

        // prepare output variable
        $jsCode = '';
        $descriptor = $namespace . '.' . $name;
        // build up the output
        $jsCode .= 'Ext.ns(\'' . $namespace . '\'); ' . "\n";
        $jsCode .= $descriptor . ' = ';
        $jsCode .= json_encode($api);
        $jsCode .= ";\n";
        $jsCode .= 'Ext.Direct.addProvider(' . $descriptor . ');' . "\n";
        // add the output to the pageRenderer
        $this->pageRenderer->addExtOnReadyCode($jsCode, true);
    }
}
