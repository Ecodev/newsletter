<?php


class Tx_Newsletter_ViewHelpers_ConfigurationViewHelper extends Tx_MvcExtjs_ViewHelpers_AbstractViewHelper {

	/**
	 * Generates some more JS to be registered / delegated to the page renderer
	 *
	 * @param array $configuration the list of configuration for the JS
	 * @return void
	 */
	public function render($configuration = array()) {

		$configuration['pageType'] = $this->getPageType($configuration['pageId']);

		$configuration = json_encode($configuration);
		$javascript = "Ext.ux.TYPO3.Newsletter.Configuration = $configuration;";

		$this->pageRenderer->addJsInlineCode("Ext.ux.TYPO3.Newsletter.Configuration", $javascript);
	}

	/**
	 * Returns the page type. Possible values: empty value, page, folder
	 *
	 * @param int $pid
	 * @return string
	 */
	protected function getPageType($pageId = 0) {
		$pageType = '';
		$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('doktype', 'pages', 'uid =' . $pageId);
		if (! empty($record['doktype']) && $record['doktype'] == 254) {
			$pageType = 'folder';
		} elseif (! empty($record['doktype'])) {
			$pageType = 'page';
		}
		return $pageType;
	}
}