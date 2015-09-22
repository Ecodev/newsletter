<?php
namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\BounceHandler;
use Ecodev\Newsletter\Domain\Model\Email;
use Ecodev\Newsletter\Domain\Repository\EmailRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;
use Ecodev\Newsletter\Tools;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/*
 * *************************************************************
 * Copyright notice
 *
 * (c) 2015
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************
 */

/**
 * Controller for the Email object
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EmailController extends ExtDirectActionController
{

    /**
     * emailRepository
     *
     * @var Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository
     */
    protected $emailRepository;

    /**
     * injectEmailRepository
     *
     * @param Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository $emailRepository
     * @return void
     */
    public function injectEmailRepository(EmailRepository $emailRepository)
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

        $this->view->setVariablesToRender(array(
            'total',
            'data',
            'success',
            'flashMessages',
        ));
        $this->view->setConfiguration(array(
            'data' => array(
                '_descendAll' => self::resolveJsonViewConfiguration(),
            ),
        ));

        $this->addFlashMessage('Loaded all Emails from Server side.', 'Emails loaded successfully', \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);
        $this->view->assign('total', $this->emailRepository->getCount($uidNewsletter));
        $this->view->assign('data', $emails);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()
            ->getAllMessagesAndFlush());
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
        readfile(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newsletter', '/Resources/Private/clear.gif'));
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
            $newsletter = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Model\\Newsletter');
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
                        $email = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Model\\Email');
                        $email->setRecipientAddress($record['email']);
                        $email->setRecipientData($record);
                    }
                }
            }
        }         // Otherwise look for the original email which was already sent
        else {
            $email = $this->emailRepository->findByAuthcode($_GET['c']);
            if ($email) {
                $newsletter = $email->getNewsletter();

                // Here we need to ensure that we have real newsletter instance because of type hinting on \Ecodev\Newsletter\Tools::getConfiguredMailer()
                if ($newsletter instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
                    $newsletter = $newsletter->_loadRealInstance();
                }
            }
        }

        // If we found everything needed, we can render the email
        $content = null;
        if ($newsletter && $email) {
            // Override some configuration
            // so we can customise the preview according to selected settings via JS,
            // and we can also prevent fake statistics when admin 'view' a sent email
            if (isset($_GET['plainConverter'])) {
                $newsletter->setPlainConverter($_GET['plainConverter']);
            }

            if (isset($_GET['injectOpenSpy'])) {
                $newsletter->setInjectOpenSpy($_GET['injectOpenSpy']);
            }

            if (isset($_GET['injectLinksSpy'])) {
                $newsletter->setInjectLinksSpy($_GET['injectLinksSpy']);
            }

            $mailer = Tools::getConfiguredMailer($newsletter, @$_GET['L']);
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
     * Unsubscribe recipient from RecipientList by registering a bounce of level \Ecodev\Newsletter\BounceHandler::NEWSLETTER_UNSUBSCRIBE
     */
    public function unsubscribeAction()
    {
        $success = false;
        $newsletter = null;
        $email = null;
        $recipientAddress = null;

        // If we have an authentification code, look for the original email which was already sent
        if (@$_GET['c']) {
            $email = $this->emailRepository->findByAuthcode($_GET['c']);
            if ($email) {
                // Mark the email as requested to be unsubscribed
                $email->setUnsubscribed(true);
                $this->emailRepository->update($email);
                $recipientAddress = $email->getRecipientAddress();

                $newsletter = $email->getNewsletter();
                if ($newsletter) {
                    $recipientList = $newsletter->getRecipientList();
                    $recipientList->registerBounce($email->getRecipientAddress(), BounceHandler::NEWSLETTER_UNSUBSCRIBE);
                    $success = true;
                    $this->notifyUnsubscribe($newsletter, $recipientList, $email);
                }
            }
        }

        // Redirect unsubscribe via config.
        $redirect = Tools::confParam('unsubscribe_redirect');
        if ($redirect !== '' || ! is_null($redirect)) {
            switch (true) {
                // If it is an URL
                case GeneralUtility::isValidUrl($redirect):
                    $this->redirectToUri($redirect);
                    exit();
                // If it is a PID.
                case is_numeric($redirect):
                    $uriBuilder = $this->controllerContext->getUriBuilder();
                    $uriBuilder->reset();
                    $uriBuilder->setUseCacheHash(false);
                    $uriBuilder->setTargetPageUid((integer) $redirect);
                    // Append the recipient address just in case you want to do something with it at the destination
                    $uriBuilder->setArguments(array(
                        'recipient' => $recipientAddress,
                    ));
                    $uri = $uriBuilder->build();
                    $this->redirectToUri($uri);
                    exit();
            }
        }

        // Else render the template.
        $this->view->assign('success', $success);
        $this->view->assign('recipientAddress', $recipientAddress);
    }

    /**
     * Sends an email to the address configured in extension settings when a recipient unsubscribe
     *
     * @param \Ecodev\Newsletter\Domain\Model\Newsletter $newsletter
     * @param \Ecodev\Newsletter\Domain\Model\RecipientList $recipientList
     * @param \Ecodev\Newsletter\Domain\Model\Email $email
     * @return void
     */
    protected function notifyUnsubscribe($newsletter, $recipientList, Email $email)
    {
        $notificationEmail = Tools::confParam('notification_email');

        // Use the page-owner as user
        if ($notificationEmail == 'user') {
            $rs = $GLOBALS['TYPO3_DB']->sql_query("SELECT email
			FROM be_users
			LEFT JOIN pages ON be_users.uid = pages.perms_userid
			WHERE pages.uid = " . $newsletter->getPid());

            list($notificationEmail) = $GLOBALS['TYPO3_DB']->sql_fetch_row($rs);
        }

        // If cannot find valid email, don't send any notification
        if (! GeneralUtility::validEmail($notificationEmail)) {
            return;
        }

        // Build email texts
        $baseUrl = 'http://' . $newsletter->getDomain();
        $urlRecipient = $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_email][' . $email->getUid() . ']=edit';
        $urlRecipientList = $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_recipientlist][' . $recipientList->getUid() . ']=edit';
        $urlNewsletter = $baseUrl . '/typo3/alt_doc.php?&edit[tx_newsletter_domain_model_newsletter][' . $newsletter->getUid() . ']=edit';
        $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('unsubscribe_notification_subject', 'newsletter');
        $body = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('unsubscribe_notification_body', 'newsletter', array(
            $email->getRecipientAddress(),
            $urlRecipient,
            $recipientList->getTitle(),
            $urlRecipientList,
            $newsletter->getTitle(),
            $urlNewsletter,
        ));

        // Actually sends email
        $message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $message->setTo($notificationEmail)
            ->setFrom(array(
            $newsletter->getSenderEmail() => $newsletter->getSenderName(),
        ))
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
    public static function resolveJsonViewConfiguration()
    {
        return array(
            '_exposeObjectIdentifier' => true,
            '_only' => array(
                'beginTime',
                'endTime',
                'authCode',
                'bounceTime',
                'openTime',
                'recipientAddress',
                'unsubscribed',
            ),
            '_descend' => array(
                'beginTime' => array(),
                'endTime' => array(),
                'openTime' => array(),
                'bounceTime' => array(),
            ),
        );
    }
}
