<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
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
***************************************************************/

/**
 * Controller for the Link object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Controller_LinkController extends Tx_MvcExtjs_MVC_Controller_ExtDirectActionController {
	
	/**
	 * linkRepository
	 * 
	 * @var Tx_Newsletter_Domain_Repository_LinkRepository
	 */
	protected $linkRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->linkRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_LinkRepository');
	}
	
		
	/**
	 * Displays all Links
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$links = $this->linkRepository->findAll();
		
		if(count($links) < 1){
			$settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
			if(empty($settings['persistence']['storagePid'])){
				$this->flashMessageContainer->add('No storagePid configured!');
			}
		}
		
		$this->view->setVariablesToRender(array('total', 'data', 'success','flashMessages'));
		$this->view->setConfiguration(array(
			'data' => array(
				'_descendAll' => self::resolveJsonViewConfiguration()
			)
		));
		
		$this->flashMessages->add('Loaded all Links from Server side.','Links loaded successfully', t3lib_FlashMessage::NOTICE);
		
		$this->view->assign('total', $links->count());
		$this->view->assign('data', $links);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
	}
	
		
	/**
	 * Displays a single Link
	 *
	 * @param Tx_Newsletter_Domain_Model_Link $link the Link to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Newsletter_Domain_Model_Link $link) {
		$this->view->assign('link', $link);
	}
	
		
	/**
	 * Creates a new Link and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Link $newLink a fresh Link object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Link
	 * @dontvalidate $newLink
	 */
	public function newAction(Tx_Newsletter_Domain_Model_Link $newLink = NULL) {
		$this->view->assign('newLink', $newLink);
	}
	
		
	/**
	 * Creates a new Link and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Link $newLink a fresh Link object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Newsletter_Domain_Model_Link $newLink) {
		$this->linkRepository->add($newLink);
		$this->flashMessageContainer->add('Your new Link was created.');
		
			
		
		$this->redirect('list');
	}
	
		
	
	/**
	 * Updates an existing Link and forwards to the index action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Link $link the Link to display
	 * @return string A form to edit a Link 
	 */
	public function editAction(Tx_Newsletter_Domain_Model_Link $link) {
		$this->view->assign('link', $link);
	}
	
		

	/**
	 * Updates an existing Link and forwards to the list action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Link $link the Link to display
	 */
	public function updateAction(Tx_Newsletter_Domain_Model_Link $link) {
		$this->linkRepository->update($link);
		$this->flashMessageContainer->add('Your Link was updated.');
		$this->redirect('list');
	}
	
		
			/**
	 * Deletes an existing Link
	 *
	 * @param Tx_Newsletter_Domain_Model_Link $link the Link to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Newsletter_Domain_Model_Link $link) {
		$this->linkRepository->remove($link);
		$this->flashMessageContainer->add('Your Link was removed.');
		$this->redirect('list');
	}
	

	/**
	 * Returns a configuration for the JsonView, that describes which fields should be rendered for
	 * a Link record.
	 * 
	 * @return array
	 */
	static public function resolveJsonViewConfiguration() {
		return array(
					'_exposeObjectIdentifier' => TRUE,
					'_only' => array('url','openedCount', 'openedPercentage'),
				);
	}
}
?>