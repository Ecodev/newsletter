<?php
require('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_tools.php");
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_bouncehandler.php");

/**
 * Sends an email to the address configured in extension settings when a recipient unsubscribe
 * @global type $LANG
 * @param Tx_Newsletter_Domain_Model_Newsletter $newsletter
 * @param Tx_Newsletter_Domain_Model_RecipientList $recipientList
 * @param Tx_Newsletter_Domain_Model_Email $email
 * @return void 
 */
function notifyUnsubscribe($newsletter, $recipientList, Tx_Newsletter_Domain_Model_Email $email) {

	$notificationEmail = tx_newsletter_tools::confParam('notification_email');
	
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

// Override settings to NOT embed images inlines (doesn't make sense for web display)
$theConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['newsletter']);
$theConf['attach_images'] = false;
$TYPO3_CONF_VARS['EXT']['extConf']['newsletter'] = serialize($theConf);

$newsletter = null;
$email = null;

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
		
		$newsletter = $email->getNewsletter();
		if ($newsletter)
		{
			$recipientList = $newsletter->getRecipientList();			
			$recipientList->registerBounce($email->getRecipientAddress() , tx_newsletter_bouncehandler::NEWSLETTER_UNSUBSCRIBE);
			notifyUnsubscribe($newsletter, $recipientList, $email);
			die('unsubscribed ' . $email->getRecipientAddress() . ' successfully.');
		}
	}
}

die('Could not unsubscribe.');
