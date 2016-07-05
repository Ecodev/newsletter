<?php

namespace Ecodev\Newsletter\ViewHelpers;

/**
 * Makes an array of configuration available in JavaScript
 */
class ConfigurationViewHelper extends AbstractViewHelper
{
    /**
     * Generates some more JS to be registered / delegated to the page renderer
     *
     * @param array $configuration the list of configuration for the JS
     */
    public function render(array $configuration)
    {
        $configuration = json_encode($configuration);
        $javascript = "Ext.ux.Ecodev.Newsletter.Configuration = $configuration;";

        $this->pageRenderer->addJsInlineCode('Ext.ux.Ecodev.Newsletter.Configuration', $javascript);
    }
}
