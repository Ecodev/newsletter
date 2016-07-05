<?php

namespace Ecodev\Newsletter\MVC\View;

/**
 * A transparent view that extends JsonView and passes on the prepared array
 * to the Ext Direct response.
 *
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
