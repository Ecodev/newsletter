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
			$newsletter->setUid(-1); // We set a fake uid so ExtJS will see it as a real record
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
	 * Creates a new Newsletter and forwards to the list action.
	 *
	 * @param Tx_Newsletter_Domain_Model_Newsletter $newNewsletter a fresh Newsletter object which has not yet been added to the repository
	 * @return void
	 * @dontverifyrequesthash
	 */
	public function createAction(Tx_Newsletter_Domain_Model_Newsletter $newNewsletter=null) {
		
		$limitTestRecipientCount = 10; // This is a low limit, technically, but it does not make sense to test a newsletter for more people than that anyway
		$recipientList = $newNewsletter->getRecipientList();
		$recipientList->init();
		$count = $recipientList->getCount();
			
		// If we attempt to create a newsletter as a test but it has too many recipient, reject it (we cannot safely send several emails wihtout slowing down respoonse and/or timeout issues)
		if ($newNewsletter->getIsTest() && $count > $limitTestRecipientCount)
		{
			$this->flashMessages->add("Test newsletter cannot be sent to $count recipients. Maximum allowed is $limitTestRecipientCount.", 'Error while sending test newsletter', t3lib_FlashMessage::ERROR);
			$this->view->assign('success', FALSE);
		}
		else
		{
			$this->newsletterRepository->add($newNewsletter);
			$this->persistenceManager->persistAll();
			$this->view->assign('success', TRUE);

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
		}
		
		
		$this->view->setVariablesToRender(array('data', 'success','flashMessages'));
		$this->view->setConfiguration(array(
			'data' =>  self::resolveJsonViewConfiguration()
		));
		
		$this->view->assign('data', $newNewsletter);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
	}
	
	/**
	 * Returns statistics to be used for timeline chart
	 * @param integer $uidNewsletter 
	 */
	public function statisticsAction($uidNewsletter) {
		$newsletter = $this->newsletterRepository->findByUid($uidNewsletter);
		
		$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
		$stats = $emailRepository->getStatistics($uidNewsletter);
		
		$stats = array(array('time' => 0, 'not_sent' => $newsletter->getEmailCount())) + $stats;
		$stats = array_values($stats); // Not a good idea to output JSON with number as keys, so reset all keys here
		
		$this->view->setVariablesToRender(array('data', 'success', 'total'));
		$this->view->setConfiguration(array(
			'data'
		));
		
		$this->view->assign('total', count($stats));
		$this->view->assign('success', true);
		$this->view->assign('data', $stats);
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
						'status',
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
