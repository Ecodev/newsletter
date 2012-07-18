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

		// We init recipientLists so we can getCount() on them
		foreach ($recipientLists as $recipientList)
		{
			$recipientList->init();
		}

		$this->view->setVariablesToRender(array('total', 'data', 'success','flashMessages'));
		$this->view->setConfiguration(array(
			'data' => array(
				'_descendAll' => self::resolveJsonViewConfiguration()
			)
		));

		$this->flashMessageContainer->add('Loaded RecipientLists from Server side.','RecipientLists loaded successfully', t3lib_FlashMessage::NOTICE);

		$this->view->assign('total', $recipientLists->count());
		$this->view->assign('data', $recipientLists);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessageContainer->getAllMessagesAndFlush());
	}

	/**
	 * Returns the list of recipient for the specified recipientList
	 * @param integer $uidRecipientList
	 * @param integer $start
	 * @param integer $limit
	 */
	public function listRecipientAction($uidRecipientList, $start, $limit)
	{
		$recipientLists = $this->recipientListRepository->findByUidInitialized($uidRecipientList);

		// Gather recipient according to defined limits
		$i = 0;
		$recipients = array();
		while ($recipient = $recipientLists->getRecipient())
		{
			if ($i++ >= $start)
			{
				$recipients[] = $recipient;
				if (count($recipients) == $limit)
				{
					break;
				}
			}
		}

		$metaData = array(
			'totalProperty' => 'total',
			'successProperty' => 'success',
			'idProperty' => 'uid',
			'root' => 'data',
			'fields' => array(),
		);

		foreach (array_keys(reset($recipients)) as $field)
		{
			$metaData['fields'][] = array('name' => $field, 'type' =>  'string');
		}

		$this->flashMessageContainer->add('Loaded Recipients from Server side.','Recipients loaded successfully', t3lib_FlashMessage::NOTICE);

		$this->view->assign('metaData', $metaData);
		$this->view->assign('total', $recipientLists->getCount());
		$this->view->assign('data', $recipients);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessageContainer->getAllMessagesAndFlush());
		$this->view->setVariablesToRender(array('metaData', 'total', 'data', 'success','flashMessages'));
	}
	
	/**
	 * Export a list of recipient and all their data
	 *
	 * @param integer $uidRecipientList
	 * @param string $authCode
	 * @return void
	 */
	public function exportAction($uidRecipientList, $authCode)
	{
		// Assert we are using supported formats
		$availableFormats = array('csv', 'xml');
		$format = $this->request->getFormat();
		if (!in_array($format, $availableFormats))
		{
			$format = reset($availableFormats);
			$this->request->setFormat($format);
		}
		
		$recipientListRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_RecipientListRepository');
		$recipientList = $recipientListRepository->findByUidInitialized($uidRecipientList);
		
		if (t3lib_div::stdAuthCode($recipientList->_getCleanProperties()) != $authCode) {
			$this->response->setStatus(401);
			return 'not authorized !';
		}

		$title = $recipientList->getTitle() . '-' . $recipientList->getUid();
		
		$this->response->setHeader('Content-Type', 'text/' . $format, TRUE);
		$this->response->setHeader('Content-Description', 'File transfer', TRUE);
		$this->response->setHeader('Content-Disposition', 'attachment; filename="' . $title . '.' . $format . '"', TRUE);

		$recipients = array();
		while ($recipient = $recipientList->getRecipient()) {
			$recipients[] = $recipient;
		}
		
		$this->view->assign('recipients', $recipients);
		$this->view->assign('title', $title);
		$this->view->assign('fields', array_keys(reset($recipients)));
	}
	
	/**
	 * Unsubscribe recipient from RecipientList by registering a bounce of level Tx_Newsletter_BounceHandler::NEWSLETTER_UNSUBSCRIBE
	 */
	public function unsubscribeAction()
	{
		$success = FALSE;
		$newsletter = null;
		$email = null;
		$recipientAddress = null;

		// If we have an authentification code, look for the original email which was already sent
		if (@$_GET['c'])
		{
			$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
			$email = $emailRepository->findByAuthcode($_GET['c']);
			if ($email)
			{
				// Mark the email as requested to be unsubscribed
				$email->setUnsubscribed(TRUE);
				$emailRepository->update($email);
				$recipientAddress = $email->getRecipientAddress();

				$newsletter = $email->getNewsletter();
				if ($newsletter)
				{
					$recipientList = $newsletter->getRecipientList();			
					$recipientList->registerBounce($email->getRecipientAddress() , Tx_Newsletter_BounceHandler::NEWSLETTER_UNSUBSCRIBE);
					$success = TRUE;
					notifyUnsubscribe($newsletter, $recipientList, $email);
				}
			}
		}
		
		$this->view->assign('success', $success);
		$this->view->assign('recipientAddress', $recipientAddress);
	}
	
	/**
	* Sends an email to the address configured in extension settings when a recipient unsubscribe
	* @global type $LANG
	* @param Tx_Newsletter_Domain_Model_Newsletter $newsletter
	* @param Tx_Newsletter_Domain_Model_RecipientList $recipientList
	* @param Tx_Newsletter_Domain_Model_Email $email
	* @return void 
	*/
	protected function notifyUnsubscribe($newsletter, $recipientList, Tx_Newsletter_Domain_Model_Email $email) {

		$notificationEmail = Tx_Newsletter_Tools::confParam('notification_email');

		// Use the page-owner as user
		if ($notificationEmail == 'user') {

			$rs = $GLOBALS['TYPO3_DB']->sql_query("SELECT email 
			FROM be_users
			LEFT JOIN pages ON be_users.uid = pages.perms_userid
			WHERE pages.uid = " . $newsletter->getPid());

			list($notificationEmail) = $GLOBALS['TYPO3_DB']->sql_fetch_row($rs);
		}

		// If cannot find valid email, don't send any notification
		if (!t3lib_div::validEmail($notificationEmail)) {
			return;
		}

		// Build email texts
		global $LANG;
		$LANG->includeLLFile('EXT:newsletter/Resources/Private/Language/locallang.xml');
		$baseUrl = 'http://' . $newsletter->getDomain();
		$urlRecipient =  $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_email][' . $email->getUid() . ']=edit';
		$urlRecipientList =  $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_recipientlist][' . $recipientList->getUid() . ']=edit';
		$urlNewsletter =  $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_newsletter][' . $newsletter->getUid() . ']=edit';
		$subject = sprintf($LANG->getLL('unsubscribe_notification_subject'));
		$body = sprintf($LANG->getLL('unsubscribe_notification_body'), $email->getRecipientAddress(), $urlRecipient, $recipientList->getTitle(), $urlRecipientList, $newsletter->getTitle(), $urlNewsletter);

		// Actually sends email
		$message = t3lib_div::makeInstance('t3lib_mail_Message');
		$message->setTo($notificationEmail)
			->setFrom(array($newsletter->getSenderEmail() => $newsletter->getSenderName()))
			->setSubject($subject)
			->setBody($body, 'text/html');
		$message->send();
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
						'count',
					)
				);
	}
}
