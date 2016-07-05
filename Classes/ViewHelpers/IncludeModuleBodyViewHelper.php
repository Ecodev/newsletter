<?php

namespace Ecodev\Newsletter\ViewHelpers;

/**
 * View helper which allows you to include a JS File.
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <newsletter:Be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <newsletter:be.moduleContainer pageTitle="foo">
 * 	<newsletter:includeModuleBody><!-- HTML Content --></newsletter:includeModuleBody>
 * </newsletter:be.moduleContainer>
 */
class IncludeModuleBodyViewHelper extends AbstractViewHelper
{
    /**
     * Calls addJsFile on the Instance of TYPO3\CMS\Core\Page\PageRenderer.
     */
    public function render()
    {
        $content = $this->renderChildren();
        $this->pageRenderer->addBodyContent($content);
    }
}
