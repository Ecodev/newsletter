<?php

namespace Ecodev\Newsletter\ViewHelpers\Be;

use Ecodev\Newsletter\ViewHelpers\AbstractViewHelper;

/**
 * View helper which allows you to create ExtBase-based modules in the style of
 * TYPO3 default modules.
 * Note: This feature is experimental!
 *
 * = Examples =
 *
 * <code title="Simple">
 * {namespace newsletter=Ecodev\Newsletter\ViewHelpers}
 * <newsletter:be.container>your additional viewhelpers inside</ext:be.container>
 * </code>
 *
 * Output:
 * "your module content" wrapped with propper head & body tags.
 * Default backend CSS styles and JavaScript will be included
 *
 * <code title="All options">
 * {namespace newsletter=Ecodev\Newsletter\ViewHelpers}
 * <newsletter:be.moduleContainer pageTitle="foo">your module content</f:be.container>
 * </code>
 */
class ModuleContainerViewHelper extends AbstractViewHelper
{
    /**
     * Don't escape anything because we will render the entire page
     */
    protected $escapeOutput = false;

    /**
     * Renders start page with template.php and pageTitle.
     *
     * @param string $pageTitle title tag of the module. Not required by default, as BE modules are shown in a frame
     * @return string
     * @see template
     * @see TYPO3\CMS\Core\Page\PageRenderer
     */
    public function render($pageTitle = '')
    {
        $doc = $this->getDocInstance();
        $this->pageRenderer->backPath = '';
        $this->pageRenderer->loadExtJS();

        // From TYPO3 8.6.0 onward t3skin is located in core (see: https://forge.typo3.org/issues/79259).
        if (version_compare(TYPO3_version, '8.6.0', '>=')) {
            $this->pageRenderer->addCssFile('sysext/core/Resources/Public/ExtJs/xtheme-t3skin.css');
        } else {
            // Anything before 8.6.0 must still use the old t3skin EXT path.
            $this->pageRenderer->addCssFile('sysext/t3skin/extjs/xtheme-t3skin.css');
        }

        $this->renderChildren();

        $this->pageRenderer->enableCompressJavaScript();
        $this->pageRenderer->enableCompressCss();
        $this->pageRenderer->enableConcatenateFiles();

        $output = $doc->startPage($pageTitle);
        $output .= $this->pageRenderer->getBodyContent();
        $output .= $doc->endPage();

        return $output;
    }
}
