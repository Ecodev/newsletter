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
	$isPreview = false;
	$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
	$email = $emailRepository->findByAuthcode($_GET['c']);
	if ($email)
	{
		$newsletter = $email->getNewsletter();
		
		// Here we need to ensure that we have real newsletter instance because the of type hinting on tx_newsletter_tools::getConfiguredMailer()
		if ($newsletter instanceof Tx_Extbase_Persistence_LazyLoadingProxy)
			$newsletter = $newsletter->_loadRealInstance();
	}
}
// Otherwise it's a preview of an email which was not sent yet, we will simulate it the best we can
else
{
	$isPreview = true;
	
	// Create a fake newsletter and configured it with given parameters
	$newsletter = new Tx_Newsletter_Domain_Model_Newsletter();
	$newsletter->setPid(@$_GET['pid']);
	$newsletter->setUidRecipientList(@$_GET['uidRecipientList']);
	if (isset($_GET['plainConverter'])) $newsletter->setPlainConverter($_GET['plainConverter']);
	$newsletter->setInjectOpenSpy(@$_GET['injectOpenSpy']);
	$newsletter->setInjectLinksSpy(@$_GET['injectLinksSpy']);
	
	if ($newsletter)
	{
		// Find the recipient
		$recipientList = $newsletter->getRecipientList();
		$recipientList->init();
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
	$mailer = tx_newsletter_tools::getConfiguredMailer($newsletter, @$_GET['L']);
	$mailer->prepare($email, $isPreview);

	if (@$_GET['plain']) {
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
