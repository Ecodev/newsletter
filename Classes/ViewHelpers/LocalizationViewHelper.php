<?php


class Tx_Newsletter_ViewHelpers_LocalizationViewHelper extends Tx_MvcExtjs_ViewHelpers_AbstractViewHelper {

	/**
	 * Calls addJsFile on the Instance of t3lib_pagerenderer.
	 * 
	 * @param string $name the file to include
	 * @param string $extKey the extension, where the file is located
	 * @param string $pathInsideExt the path to the file relative to the ext-folder
	 * @return void
	 */
	public function render($name = 'locallang.xml', $extKey = NULL, $pathInsideExt = 'Resources/Private/Language/') {
		if ($extKey == NULL) {
			$extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();
		}
		$extPath = t3lib_extMgm::extPath($extKey);

		$filePath = $extPath . $pathInsideExt . $name;
		
		$localizations = $this->getLocalizations($filePath);
		$localizations = json_encode($localizations);
		$javacript = "Ext.ux.TYPO3.Newsletter.Language = $localizations;";
		
		$this->pageRenderer->addJsInlineCode($filePath, $javacript);
	}
	
	protected function getLocalizations($filePath)
	{
		global $LANG;
		global $LOCAL_LANG;
		
		// Language inclusion
		$LANG->includeLLFile($filePath);
		if (isset($LOCAL_LANG[$LANG->lang]) && !empty($LOCAL_LANG[$LANG->lang])) {
			return $LOCAL_LANG[$LANG->lang];
		}
		else {
			throw new Exception('No language file has been found', 1276451853);
		}
	}
}