<?php

/**
 * Makes an array of configuration available in JavaScript
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_ViewHelpers_ConfigurationViewHelper extends Tx_Newsletter_ViewHelpers_AbstractViewHelper
{

    /**
     * Generates some more JS to be registered / delegated to the page renderer
     *
     * @param array $configuration the list of configuration for the JS
     * @return void
     */
    public function render(array $configuration)
    {

        $configuration = json_encode($configuration);
        $javascript = "Ext.ux.TYPO3.Newsletter.Configuration = $configuration;";

        $this->pageRenderer->addJsInlineCode("Ext.ux.TYPO3.Newsletter.Configuration", $javascript);
    }

}
