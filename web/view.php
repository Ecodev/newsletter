<?php
require('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_tools.php");

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
		$newsletter = $email->getNewsletter();
	}
}
// Otherwise it's a preview of an email which was not sent yet, we will simulate it
else
{
	$newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');
	$newsletter = $newsletterRepository->findByUid(@$_GET['newsletter']);
	
	if ($newsletter)
	{
		// Find the recipient
		$recipientList = $newsletter->getRecipientListConcreteInstance();
		while ($record = $recipientList->getRecipient())
		{
			// Got him
			if ($record['email'] == $_GET['email'])
			{
				// Build a fake email
				$email = new Tx_Newsletter_Domain_Model_Email();
				$email->setRecipientAddress($record['email']);
				$email->setRecipientData($record);
			}
		}
	}
}

// If we found everything needed, we can render the email
if ($newsletter && $email)
{
	$mailer = tx_newsletter_tools::getConfiguredMailer($newsletter);
	$mailer->prepare($email);

	if (@$_GET['type'] == 'plain') {
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><pre>';
		echo $mailer->getPlain();
		echo '</pre></body></html>';
	} else {
		echo $mailer->getHtml();
	}
}
else
{
	die('Newsletter could not be found :-(');
}
