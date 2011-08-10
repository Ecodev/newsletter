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
 * Controller for the Newsletter object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Newsletter_Controller_NewsletterController extends Tx_MvcExtjs_MVC_Controller_ExtDirectActionController {

	/**
	 * newsletterRepository
	 * 
	 * @var Tx_Newsletter_Domain_Repository_NewsletterRepository
	 */
	protected $newsletterRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');


		// Set default value of PID to know where to store/look for newsletter
		$this->pid = filter_var(t3lib_div::_GET('id'), FILTER_VALIDATE_INT, array("min_range"=> 0));
		if (!$this->pid) {
			$this->pid = 0;
		}
		parent::initializeAction();
	}
		
	/**
	 * Displays all Newsletters
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$newsletters = $this->newsletterRepository->findAllByPid($this->pid);
		
		$this->view->setVariablesToRender(array('total', 'data', 'success','flashMessages'));
		$this->view->setConfiguration(array(
			'data' => array(
				'_descendAll' => self::resolveJsonViewConfiguration()
			)
		));
		
		$this->flashMessages->add('Loaded Newsletters from Server side.','Newsletters loaded successfully', t3lib_FlashMessage::NOTICE);
		
		$this->view->assign('total', $newsletters->count());
		$this->view->assign('data', $newsletters);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
	}
	
	/**
	 * Displays the newsletter used as model for plannification
	 *
	 * @return string The rendered list view
	 */
	public function listPlannedAction() {
		$newsletter = $this->newsletterRepository->getLatest($this->pid);
		if (!$newsletter)
		{
			$newsletter = t3lib_div::makeInstance('Tx_Newsletter_Domain_Model_Newsletter');
			$newsletter->setPid($this->pid);
		}
		
		$this->view->setVariablesToRender(array('total', 'data', 'success'));
		$this->view->setConfiguration(array(
			'data' => self::resolvePlannedJsonViewConfiguration()
		));
		
		$this->view->assign('total', 1);
		$this->view->assign('data', $newsletter);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
	}
	
		
	/**
	 * Displays a single Newsletter
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->view->assign('newsletter', $newsletter);
	}
	
		
	/**
	 * Creates a new Newsletter and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newNewsletter a fresh Newsletter object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Newsletter
	 * @dontvalidate $newNewsletter
	 */
	public function newAction(Tx_Newsletter_Domain_Model_Newsletter $newNewsletter = NULL) {
		$this->view->assign('newNewsletter', $newNewsletter);
	}
	
		
	/**
	 * Creates a new Newsletter and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newNewsletter a fresh Newsletter object which has not yet been added to the repository
	 * @return void
	 * @dontverifyrequesthash
	 */
	public function createAction(Tx_Newsletter_Domain_Model_Newsletter $newNewsletter=null) {
		$this->newsletterRepository->add($newNewsletter);
		$this->persistenceManager->persistAll();
		
		// If it is test newsletter, send it immediately
		if ($newNewsletter->getIsTest())
		{
			try {
				// Fill the spool and run the queue
				tx_newsletter_tools::createSpool($newNewsletter);
				tx_newsletter_tools::runSpoolOne($newNewsletter);

				$this->flashMessages->add('Test newsletter has been sent.', 'Test newsletter sent', t3lib_FlashMessage::OK);
			}
			catch (Exception $exception)
			{
				$this->flashMessages->add($exception->getMessage(), 'Error while sending test newsletter', t3lib_FlashMessage::ERROR);
			}
		}
		else
		{
			$this->flashMessages->add('Newsletter has been queued and will be sent soon.', 'Newsletter queued', t3lib_FlashMessage::OK);
		}
		
		
		$this->view->setVariablesToRender(array('data', 'success','flashMessages'));
		$this->view->setConfiguration(array(
			'data' =>  self::resolveJsonViewConfiguration()
		));
		
		$this->view->assign('success',TRUE);
		$this->view->assign('data', $newNewsletter);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
	}	
	
	/**
	 * Updates an existing Newsletter and forwards to the index action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to display
	 * @return string A form to edit a Newsletter 
	 */
	public function editAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->view->assign('newsletter', $newsletter);
	}
	
		

	/**
	 * Updates an existing Newsletter and forwards to the list action afterwards.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to display
	 */
	public function updateAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->newsletterRepository->update($newsletter);
		$this->flashMessageContainer->add('Your Newsletter was updated.');
		$this->redirect('list');
	}
	
		
	/**
	 * Deletes an existing Newsletter
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter the Newsletter to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Newsletter_Domain_Model_Newsletter $newsletter) {
		$this->newsletterRepository->remove($newsletter);
		$this->flashMessageContainer->add('Your Newsletter was removed.');
		$this->redirect('list');
	}
	
	/**
	 * Returns a configuration for the JsonView, that describes which fields should be rendered for
	 * a Newsletter record.
	 * 
	 * @return array
	 */
	static public function resolveJsonViewConfiguration() {
		return array(
					'_exposeObjectIdentifier' => TRUE,
					'_only' => array(
						'pid',
						'beginTime',
						'bounceAccount',
						'domain',
						'endTime',
						'injectLinksSpy',
						'injectOpenSpy',
						'isTest',
						'plainConverter',
						'plannedTime',
						'repetition',
						'senderEmail',
						'senderName',
						'title',
						'emailCount',
						'emailNotSentCount',
						'emailSentCount',
						'emailOpenedCount',
						'emailBouncedCount',
					),
					'_descend' => array(
						'beginTime' => array(),
						'endTime' => array(),
						'plannedTime' => array(),
						)
				);
	}
	
	static public function resolvePlannedJsonViewConfiguration() {
		return array(
					'_exposeObjectIdentifier' => TRUE,
					'_only' => array(
						'pid',
						'beginTime',
						'uidBounceAccount',
						'uidRecipientList',
						'domain',
						'endTime',
						'injectLinksSpy',
						'injectOpenSpy',
						'isTest',
						'plainConverter',
						'plannedTime',
						'repetition',
						'senderEmail',
						'senderName',
						'title',
						'validatedContent',
					),
					'_descend' => array(
						'beginTime' => array(),
						'endTime' => array(),
						'plannedTime' => array(),
						'validatedContent' => array(
							'_only'=> array(
								'errors',
								'warnings', 
								'infos')
							),
						)
				);
	}
}
?>