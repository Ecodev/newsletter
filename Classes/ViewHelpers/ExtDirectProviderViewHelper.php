<?php

namespace Ecodev\Newsletter\ViewHelpers;

/**
 * View helper which allows
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo">
 * 	<newsletter:includeDirectApi />
 * </newsletter:be.moduleContainer>
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
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $this->apiService = $objectManager->get(\Ecodev\Newsletter\MVC\ExtDirect\Api::class);
    }

    /**
     * Generates a Ext.Direct API descriptor and adds it to the pagerenderer.
     * Also calls Ext.Direct.addProvider() on itself (at js side).
     * The remote API is directly useable.
     *
     * @param string $name the name for the javascript variable
     * @param string $namespace the namespace the variable is placed
     * @param string $routeUrl you can specify a URL that acts as router
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
