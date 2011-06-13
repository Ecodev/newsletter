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
 * Controller for the Email object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Controller_EmailController extends Tx_MvcExtjs_MVC_Controller_ExtDirectActionController {
	
	/**
	 * emailRepository
	 * 
	 * @var Tx_Newsletter_Domain_Repository_EmailRepository
	 */
	protected $emailRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
	}
	
	
		
	/**
	 * Displays all Emails
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$emails = $this->emailRepository->findAll();
		
		if(count($emails) < 1){
			$settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
			if(empty($settings['persistence']['storagePid'])){
				$this->flashMessageContainer->add('No storagePid configured!');
			}
		}
		
		$this->view->assign('emails', $emails);
	}
	
		
	/**
	 * Displays a single Email
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $email the Email to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Newsletter_Domain_Model_Email $email) {
		$this->view->assign('email', $email);
	}
	
		
	/**
	 * Creates a new Email and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $newEmail a fresh Email object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Email
	 * @dontvalidate $newEmail
	 */
	public function newAction(Tx_Newsletter_Domain_Model_Email $newEmail = NULL) {
		$this->view->assign('newEmail', $newEmail);
	}
	
		
	/**
	 * Creates a new Email and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $newEmail a fresh Email object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Newsletter_Domain_Model_Email $newEmail) {
		$this->emailRepository->add($newEmail);
		$this->flashMessageContainer->add('Your new Email was created.');
		
			
		
		$this->redirect('list');
	}
	
		
	
	/**
	 * Updates an existing Email and forwards to the index action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $email the Email to display
	 * @return string A form to edit a Email 
	 */
	public function editAction(Tx_Newsletter_Domain_Model_Email $email) {
		$this->view->assign('email', $email);
	}
	
		

	/**
	 * Updates an existing Email and forwards to the list action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $email the Email to display
	 */
	public function updateAction(Tx_Newsletter_Domain_Model_Email $email) {
		$this->emailRepository->update($email);
		$this->flashMessageContainer->add('Your Email was updated.');
		$this->redirect('list');
	}
	
		
			/**
	 * Deletes an existing Email
	 *
	 * @param Tx_Newsletter_Domain_Model_Email $email the Email to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Newsletter_Domain_Model_Email $email) {
		$this->emailRepository->remove($email);
		$this->flashMessageContainer->add('Your Email was removed.');
		$this->redirect('list');
	}
	

}
?>