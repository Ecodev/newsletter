<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
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
 * ************************************************************* */

/**
 * Controller for the Email object
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Controller_EmailController extends Tx_Newsletter_MVC_Controller_ExtDirectActionController
{

    /**
     * emailRepository
     *
     * @var Tx_Newsletter_Domain_Repository_EmailRepository
     */
    protected $emailRepository;

    /**
     * injectEmailRepository
     *
     * @param Tx_Newsletter_Domain_Repository_EmailRepository $emailRepository
     * @return void
     */
    public function injectEmailRepository(Tx_Newsletter_Domain_Repository_EmailRepository $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }

    /**
     * Displays all Emails
     *
     * @param integer $uidNewsletter
     * @param integer $start
     * @param integer $limit
     * @return string The rendered list view
     */
    public function listAction($uidNewsletter, $start, $limit)
    {
        $emails = $this->emailRepository->findAllByNewsletter($uidNewsletter, $start, $limit);

        $this->view->setVariablesToRender(array('total', 'data', 'success', 'flashMessages'));
        $this->view->setConfiguration(array(
            'data' => array(
                '_descendAll' => self::resolveJsonViewConfiguration()
            )
        ));

        $this->flashMessageContainer->add('Loaded all Emails from Server side.', 'Emails loaded successfully', t3lib_FlashMessage::NOTICE);
        ;
        $this->view->assign('total', $this->emailRepository->getCount($uidNewsletter));
        $this->view->assign('data', $emails);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->flashMessageContainer->getAllMessagesAndFlush());
    }

    /**
     * Register when an email was opened
     * For this method we don't use extbase parameters system to have an URL as short as possible
     */
    public function openedAction()
    {

        $this->emailRepository->registerOpen(@$_REQUEST['c']);

        // Send one transparent pixel, so the end-user sees nothing at all
        header('Content-type: image/gif');
        readfile(t3lib_extMgm::extPath('newsletter', '/Resources/Private/clear.gif'));
        die();
    }

    /**
     * Show a preview or the final version of an email
     * For this method we don't use extbase parameters system to have an URL as short as possible
     */
    public function showAction()
    {
        // Override settings to NOT embed images inlines (doesn't make sense for web display)
        global $TYPO3_CONF_VARS;
        $theConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['newsletter']);
        $theConf['attach_images'] = false;
        $TYPO3_CONF_VARS['EXT']['extConf']['newsletter'] = serialize($theConf);

        $newsletter = null;
        $email = null;
        $isPreview = empty($_GET['c']); // If we don't have an authentification code, we are in preview mode
        // If it's a preview, an email which was not sent yet, we will simulate it the best we can
        if ($isPreview) {
            // Create a fake newsletter and configure it with given parameters
            $newsletter = $this->objectManager->create('Tx_Newsletter_Domain_Model_Newsletter');
            $newsletter->setPid(@$_GET['pid']);
            $newsletter->setUidRecipientList(@$_GET['uidRecipientList']);

            if ($newsletter) {
                // Find the recipient
                $recipientList = $newsletter->getRecipientList();
                $recipientList->init();
                while ($record = $recipientList->getRecipient()) {
                    // Got him
                    if ($record['email'] == $_GET['email']) {
                        // Build a fake email
                        $email = $this->objectManager->create('Tx_Newsletter_Domain_Model_Email');
                        $email->setRecipientAddress($record['email']);
                        $email->setRecipientData($record);
                    }
                }
            }
        }
        // Otherwise look for the original email which was already sent
        else {
            $email = $this->emailRepository->findByAuthcode($_GET['c']);
            if ($email) {
                $newsletter = $email->getNewsletter();

                // Here we need to ensure that we have real newsletter instance because of type hinting on Tx_Newsletter_Tools::getConfiguredMailer()
                if ($newsletter instanceof Tx_Extbase_Persistence_LazyLoadingProxy)
                    $newsletter = $newsletter->_loadRealInstance();
            }
        }

        // If we found everything needed, we can render the email
        $content = null;
        if ($newsletter && $email) {
            // Override some configuration
            // so we can customise the preview according to selected settings via JS,
            // and we can also prevent fake statistics when admin 'view' a sent email
            if (isset($_GET['plainConverter']))
                $newsletter->setPlainConverter($_GET['plainConverter']);
            if (isset($_GET['injectOpenSpy']))
                $newsletter->setInjectOpenSpy($_GET['injectOpenSpy']);
            if (isset($_GET['injectLinksSpy']))
                $newsletter->setInjectLinksSpy($_GET['injectLinksSpy']);

            $mailer = Tx_Newsletter_Tools::getConfiguredMailer($newsletter, @$_GET['L']);
            $mailer->prepare($email, $isPreview);

            if (@$_GET['plain']) {
                $content = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><pre>';
                $content .= $mailer->getPlain();
                $content .= '</pre></body></html>';
            } else {
                $content = $mailer->getHtml();
            }
        }

        $this->view->assign('content', $content);
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
        if (@$_GET['c']) {
            $email = $this->emailRepository->findByAuthcode($_GET['c']);
            if ($email) {
                // Mark the email as requested to be unsubscribed
                $email->setUnsubscribed(TRUE);
                $this->emailRepository->update($email);
                $recipientAddress = $email->getRecipientAddress();

                $newsletter = $email->getNewsletter();
                if ($newsletter) {
                    $recipientList = $newsletter->getRecipientList();
                    $recipientList->registerBounce($email->getRecipientAddress(), Tx_Newsletter_BounceHandler::NEWSLETTER_UNSUBSCRIBE);
                    $success = TRUE;
                    $this->notifyUnsubscribe($newsletter, $recipientList, $email);
                }
            }
        }

        $this->view->assign('success', $success);
        $this->view->assign('recipientAddress', $recipientAddress);
    }

    /**
     * Sends an email to the address configured in extension settings when a recipient unsubscribe
     * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter
     * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList
     * @param Tx_Newsletter_Domain_Model_Email $email
     * @return void
     */
    protected function notifyUnsubscribe($newsletter, $recipientList, Tx_Newsletter_Domain_Model_Email $email)
    {

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
        $baseUrl = 'http://' . $newsletter->getDomain();
        $urlRecipient = $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_email][' . $email->getUid() . ']=edit';
        $urlRecipientList = $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_recipientlist][' . $recipientList->getUid() . ']=edit';
        $urlNewsletter = $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_newsletter][' . $newsletter->getUid() . ']=edit';
        $subject = Tx_Extbase_Utility_Localization::translate('unsubscribe_notification_subject', 'newsletter');
        $body = Tx_Extbase_Utility_Localization::translate('unsubscribe_notification_body', 'newsletter', array($email->getRecipientAddress(), $urlRecipient, $recipientList->getTitle(), $urlRecipientList, $newsletter->getTitle(), $urlNewsletter));

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
     * a Email record.
     *
     * @return array
     */
    static public function resolveJsonViewConfiguration()
    {
        return array(
            '_exposeObjectIdentifier' => TRUE,
            '_only' => array('beginTime', 'endTime', 'authCode', 'bounceTime', 'openTime', 'recipientAddress', 'unsubscribed'),
            '_descend' => array(
                'beginTime' => array(),
                'endTime' => array(),
                'openTime' => array(),
                'bounceTime' => array(),
            )
        );
    }

}
