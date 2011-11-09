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
		
		$this->flashMessages->add('Loaded RecipientLists from Server side.','RecipientLists loaded successfully', t3lib_FlashMessage::NOTICE);
		
		$this->view->assign('total', $recipientLists->count());
		$this->view->assign('data', $recipientLists);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
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
		
		$this->flashMessages->add('Loaded Recipients from Server side.','Recipients loaded successfully', t3lib_FlashMessage::NOTICE);
		
		$this->view->assign('metaData', $metaData);
		$this->view->assign('total', $recipientLists->getCount());
		$this->view->assign('data', $recipients);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessages->getAllMessagesAndFlush());
		$this->view->setVariablesToRender(array('metaData', 'total', 'data', 'success','flashMessages'));
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
