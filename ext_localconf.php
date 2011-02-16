<?php

// ExtDirect API
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ExtDirect']['TYPO3.Newsletter.Remote'] = 'EXT:newsletter/class.tx_newsletter_remote.php:tx_newsletter_remote';

# Register Ajax function
#$TYPO3_CONF_VARS['BE']['AJAX']['NewsletterController::getListOfNewsletter'] = 'EXT:newsletter/class.tx_newsletter_remote.php:tx_newsletter_remote->getListOfNewsletter';

$tempFilePath = t3lib_extMgm::extPath('newsletter');
$TYPO3_CONF_VARS['EXTCONF']['newsletter']['includeClassFiles'] = array(
    $tempFilePath.'class.tx_newsletter_target_beusers.php',
    $tempFilePath.'class.tx_newsletter_target_csvfile.php',
    $tempFilePath.'class.tx_newsletter_target_fegroups.php',
    $tempFilePath.'class.tx_newsletter_target_fepages.php',
    $tempFilePath.'class.tx_newsletter_target_html.php',
    $tempFilePath.'class.tx_newsletter_target_csvlist.php',
    $tempFilePath.'class.tx_newsletter_target_csvurl.php',
    $tempFilePath.'class.tx_newsletter_target_rawsql.php',
    $tempFilePath.'class.tx_newsletter_target_ttaddress.php',
    $tempFilePath.'class.tx_newsletter_plain_html2text.php',
    $tempFilePath.'class.tx_newsletter_plain_lynx.php',
    $tempFilePath.'class.tx_newsletter_plain_simple.php',
    $tempFilePath.'class.tx_newsletter_plain_template.php');
    
   
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Mailer'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Mailer'] = 'TYPO3 CMS - newsletter extension';
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Precedence'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Precedence'] = 'bulk';
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Provided-by'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Sponsored-by'] = 'http://www.casalogic.dk/ - Open Source Experts.';
$TYPO3_CONF_VARS['EXTCONF']['kickstarter']['sections']['tx_newsletter_domain_model_recipientlist'] = array(
    'classname' => 'tx_newsletter_section_targets',
    'filepath' => 'EXT:newsletter/sections/class.tx_newsletter_section_targets.php',
    'title' => 'Make a new newsletter target',
    'description' => 'Create additional newsletter targets, based on your own tables.',
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_newsletter_NewsletterTask'] = array(
        'extension'        => $_EXTKEY,
        'title'            => 'Run TC Newsletter',
        'description'      => 'Send email',
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_newsletter_NewsletterbounceTask'] = array(
        'extension'        => $_EXTKEY,
        'title'            => 'Run TC Newsletter Bounce',
        'description'      => 'Fetch bounce statistic',
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'TxNewsletterM1',
	array(
		// controller Actions declared
		'Newsletter' => 'index, show, new, create, edit, update, delete',
		'Statistic' => 'index, show, new, create, edit, update, delete',
	),
	array(
		// non cachable actions -> change this to 'create, update, delete'
		'Newsletter' => 'index, show, new, create, edit, update, delete',
		'Statistic' => 'index, show, new, create, edit, update, delete',
	)
);

