<?php

namespace Ecodev\Newsletter\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Dennis Ahrens <dennis.ahrens@googlemail.com>
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
 * a ViewHelper that holds a pageRenderer Object as instance variable
 *
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
abstract class AbstractViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper
{
    /**
     * @var TYPO3\CMS\Core\Page\PageRenderer
     */
    protected $pageRenderer;

    /**
     * @see typo3/sysext/fluid/Classes/Core/ViewHelper/\TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper#initialize()
     */
    public function initialize()
    {
        $this->pageRenderer = $this->getPageRendererCompatibility();
    }

    private function getPageRendererCompatibility()
    {
        // For TYPO3 7.4 and newer we can use a direct method, for older version 6.2-7.3
        if (is_callable([$this, 'getPageRenderer'])) {
            return $this->getPageRenderer();
        } else {
            return $this->getDocInstance()->getPageRenderer();
        }
    }
}
