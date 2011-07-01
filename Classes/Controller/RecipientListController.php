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
 * Controller for the RecipientList object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Controller_RecipientListController extends Tx_MvcExtjs_MVC_Controller_ExtDirectActionController {
	
	/**
	 * recipientListRepository
	 * 
	 * @var Tx_Newsletter_Domain_Repository_RecipientListRepository
	 */
	protected $recipientListRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
	}
	
	
		
	/**
	 * Displays all RecipientLists
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$recipientLists = $this->recipientListRepository->findAll();
		
		if(count($recipientLists) < 1){
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
		
		$this->flashMessages->add('Loaded RecipientLists from Server side.','RecipientLists loaded successfully', t3lib_FlashMessage::NOTICE);
		
		$this->view->assign('total', $recipientLists->count());
		$this->view->assign('data', $recipientLists);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
	}
	
		
	/**
	 * Displays a single RecipientList
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList the RecipientList to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		$this->view->assign('recipientList', $recipientList);
	}
	
		
	/**
	 * Creates a new RecipientList and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $newRecipientList a fresh RecipientList object which has not yet been added to the repository
	 * @return string An HTML form for creating a new RecipientList
	 * @dontvalidate $newRecipientList
	 */
	public function newAction(Tx_Newsletter_Domain_Model_RecipientList $newRecipientList = NULL) {
		$this->view->assign('newRecipientList', $newRecipientList);
	}
	
		
	/**
	 * Creates a new RecipientList and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $newRecipientList a fresh RecipientList object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Newsletter_Domain_Model_RecipientList $newRecipientList) {
		$this->recipientListRepository->add($newRecipientList);
		$this->flashMessageContainer->add('Your new RecipientList was created.');
		
		$this->redirect('list');
	}
		
	
	/**
	 * Updates an existing RecipientList and forwards to the index action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList the RecipientList to display
	 * @return string A form to edit a RecipientList 
	 */
	public function editAction(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		$this->view->assign('recipientList', $recipientList);
	}
		

	/**
	 * Updates an existing RecipientList and forwards to the list action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList the RecipientList to display
	 */
	public function updateAction(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		$this->recipientListRepository->update($recipientList);
		$this->flashMessageContainer->add('Your RecipientList was updated.');
		$this->redirect('list');
	}
	
		
	/**
	 * Deletes an existing RecipientList
	 *
	 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList the RecipientList to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		$this->recipientListRepository->remove($recipientList);
		$this->flashMessageContainer->add('Your RecipientList was removed.');
		$this->redirect('list');
	}
	

	/**
	 * Returns a configuration for the JsonView, that describes which fields should be rendered for
	 * a RecipientList record.
	 * 
	 * @return array
	 */
	static public function resolveJsonViewConfiguration() {
		return array(
					'_exposeObjectIdentifier' => TRUE,
					'_only' => array(
						'title',
						'plainOnly',
						'lang',
						'type',
					)
				);
	}
}
?>