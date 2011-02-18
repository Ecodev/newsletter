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
 * Controller for the BounceAccount object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Controller_BounceAccountController extends Tx_Extbase_MVC_Controller_ActionController {
	
	/**
	 * bounceAccountRepository
	 * 
	 * @var Tx_Newsletter_Domain_Repository_BounceAccountRepository
	 */
	protected $bounceAccountRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->bounceAccountRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_BounceAccountRepository');
	}
	
	
		
	/**
	 * Displays all BounceAccounts
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$bounceAccounts = $this->bounceAccountRepository->findAll();
		
		if(count($bounceAccounts) < 1){
			$settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
			if(empty($settings['persistence']['storagePid'])){
				$this->flashMessageContainer->add('No storagePid configured!');
			}
		}
		
		$this->view->assign('bounceAccounts', $bounceAccounts);
	}
	
		
	/**
	 * Displays a single BounceAccount
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount the BounceAccount to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount) {
		$this->view->assign('bounceAccount', $bounceAccount);
	}
	
		
	/**
	 * Creates a new BounceAccount and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $newBounceAccount a fresh BounceAccount object which has not yet been added to the repository
	 * @return string An HTML form for creating a new BounceAccount
	 * @dontvalidate $newBounceAccount
	 */
	public function newAction(Tx_Newsletter_Domain_Model_BounceAccount $newBounceAccount = NULL) {
		$this->view->assign('newBounceAccount', $newBounceAccount);
	}
	
		
	/**
	 * Creates a new BounceAccount and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $newBounceAccount a fresh BounceAccount object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Newsletter_Domain_Model_BounceAccount $newBounceAccount) {
		$this->bounceAccountRepository->add($newBounceAccount);
		$this->flashMessageContainer->add('Your new BounceAccount was created.');
		
			
		
		$this->redirect('list');
	}
	
		
	
	/**
	 * Updates an existing BounceAccount and forwards to the index action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount the BounceAccount to display
	 * @return string A form to edit a BounceAccount 
	 */
	public function editAction(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount) {
		$this->view->assign('bounceAccount', $bounceAccount);
	}
	
		

	/**
	 * Updates an existing BounceAccount and forwards to the list action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount the BounceAccount to display
	 */
	public function updateAction(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount) {
		$this->bounceAccountRepository->update($bounceAccount);
		$this->flashMessageContainer->add('Your BounceAccount was updated.');
		$this->redirect('list');
	}
	
		
			/**
	 * Deletes an existing BounceAccount
	 *
	 * @param Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount the BounceAccount to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Newsletter_Domain_Model_BounceAccount $bounceAccount) {
		$this->bounceAccountRepository->remove($bounceAccount);
		$this->flashMessageContainer->add('Your BounceAccount was removed.');
		$this->redirect('list');
	}
	

}
?>