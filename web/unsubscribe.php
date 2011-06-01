<?php
require('browserrun.php');
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_tools.php");
require_once(t3lib_extMgm::extPath('newsletter')."class.tx_newsletter_bouncehandler.php");

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
		if ($newsletter)
		{
			$recipientList = $newsletter->getRecipientListConcreteInstance();			
			$recipientList->disableReceiver($email->getRecipientAddress() , tx_newsletter_bouncehandler::NEWSLETTER_UNSUBSCRIBE);
			die('unsubscribed ' . $email->getRecipientAddress() . ' successfully.');
		}
	}
}

die('Could not unsubscribe.');
