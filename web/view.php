<?php
require('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_tools.php");

// Override settings to NOT embed images inlines (doesn't make sense for web display)
$theConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['newsletter']);
$theConf['attach_images'] = false;
$TYPO3_CONF_VARS['EXT']['extConf']['newsletter'] = serialize($theConf);

$newsletter = null;
$email = null;
$isPreview = empty($_GET['c']); // If we don't have an authentification code, we are in preview mode

// If it's a preview of an email which was not sent yet, we will simulate it the best we can
if ($isPreview)
{
	// Create a fake newsletter and configure it with given parameters
	$newsletter = new Tx_Newsletter_Domain_Model_Newsletter();
	$newsletter->setPid(@$_GET['pid']);
	$newsletter->setUidRecipientList(@$_GET['uidRecipientList']);
	
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
// Otherwise look for the original email which was already sent
else
{
	$emailRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_EmailRepository');
	$email = $emailRepository->findByAuthcode($_GET['c']);
	if ($email)
	{
		$newsletter = $email->getNewsletter();
		
		// Here we need to ensure that we have real newsletter instance because of type hinting on tx_newsletter_tools::getConfiguredMailer()
		if ($newsletter instanceof Tx_Extbase_Persistence_LazyLoadingProxy)
			$newsletter = $newsletter->_loadRealInstance();
	}
}

// If we found everything needed, we can render the email
if ($newsletter && $email)
{
	// Override some configuration
	// so we can customise the preview according to selected settings via JS,
	// and we can also prevent fake statistics when admin 'view' a sent email
	if (isset($_GET['plainConverter'])) $newsletter->setPlainConverter($_GET['plainConverter']);
	if (isset($_GET['injectOpenSpy'])) $newsletter->setInjectOpenSpy($_GET['injectOpenSpy']);
	if (isset($_GET['injectLinksSpy'])) $newsletter->setInjectLinksSpy($_GET['injectLinksSpy']);
	
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
