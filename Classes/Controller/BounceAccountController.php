<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012
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
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Controller_BounceAccountController extends Tx_Newsletter_MVC_Controller_ExtDirectActionController {

	/**
	 * bounceAccountRepository
	 *
	 * @var Tx_Newsletter_Domain_Repository_BounceAccountRepository
	 */
	protected $bounceAccountRepository;

	/**
     * injectBounceAccounRepository
	 *
	 * @param Tx_Newsletter_Domain_Repository_BounceAccountRepository $bounceAccountRepository
	 * @return void
	 */
	public function injectBounceAccounRepository(Tx_Newsletter_Domain_Repository_BounceAccountRepository $bounceAccountRepository) {
		$this->bounceAccountRepository = $bounceAccountRepository;
	}

	/**
	 * Displays all BounceAccounts
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		$bounceAccounts = $this->bounceAccountRepository->findAll();

		$this->view->setVariablesToRender(array('total', 'data', 'success','flashMessages'));
		$this->view->setConfiguration(array(
			'data' => array(
				'_descendAll' => self::resolveJsonViewConfiguration()
			)
		));

		$this->flashMessageContainer->add('Loaded BounceAccounts from Server side.','BounceAccounts loaded successfully', t3lib_FlashMessage::NOTICE);

		$this->view->assign('total', $bounceAccounts->count());
		$this->view->assign('data', $bounceAccounts);
		$this->view->assign('success', true);
		$this->view->assign('flashMessages', $this->flashMessageContainer->getAllMessagesAndFlush());
	}


	/**
	 * Returns a configuration for the JsonView, that describes which fields should be rendered for
	 * a BounceAccount record.
	 *
	 * @return array
	 */
	static public function resolveJsonViewConfiguration() {
		return array(
					'_exposeObjectIdentifier' => TRUE,
					'_only' => array(
						'email',
						'server',
						'protocol',
						'username',
					)
				);
	}
}
