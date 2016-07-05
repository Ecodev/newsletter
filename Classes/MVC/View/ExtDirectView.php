<?php

namespace Ecodev\Newsletter\MVC\View;

/* *
 * This script belongs to the FLOW3 package "ExtJS".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A transparent view that extends JsonView and passes on the prepared array
 * to the Ext Direct response.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class ExtDirectView extends JsonView
{
    /**
     * Renders the Ext Direct view by delegating to the JsonView
     * for rendering a serializable array.
     *
     * @return string An empty string
     */
    public function render()
    {
        $result = $this->renderArray();
        $this->controllerContext->getResponse()->setResult($result);
        $this->controllerContext->getResponse()->setSuccess(true);
    }

    /**
     * Assigns errors to the view and converts them to a format that Ext JS
     * understands.
     *
     * @param array $errors Errors e.g. from mapping results
     */
    public function assignErrors(array $errors)
    {
        $result = [];
        foreach ($errors as $argumentName => $argumentError) {
            foreach ($argumentError->getErrors() as $propertyName => $propertyError) {
                $message = '';
                foreach ($propertyError->getErrors() as $error) {
                    $message .= $error->getMessage();
                }
                $result[$propertyName] = $message;
            }
        }
        $this->assign('value', [
            'errors' => $result,
            'success' => false,
        ]);
    }
}
