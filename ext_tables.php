<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');





t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Newsletter');




t3lib_extMgm::addLLrefForTCAdescr('tx_newsletter_domain_model_newsletter', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_newsletter.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_newsletter_domain_model_newsletter');
$TCA['tx_newsletter_domain_model_newsletter'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_newsletter',
		'label' 			=> 'planned_time',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'delete' 			=> 'deleted',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Newsletter.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_newsletter.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_newsletter_domain_model_bounceaccount', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_bounceaccount.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_newsletter_domain_model_bounceaccount');
$TCA['tx_newsletter_domain_model_bounceaccount'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_bounceaccount',
		'label' 			=> 'email',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'delete' 			=> 'deleted',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/BounceAccount.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_bounceaccount.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_newsletter_domain_model_recipientlist', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_recipientlist.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_newsletter_domain_model_recipientlist');
$TCA['tx_newsletter_domain_model_recipientlist'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_recipientlist',
		'label' 			=> 'title',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'delete' 			=> 'deleted',
		'type'				=> 'type',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/RecipientList.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_recipientlist.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_newsletter_domain_model_email', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_email.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_newsletter_domain_model_email');
$TCA['tx_newsletter_domain_model_email'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_email',
		'label' 			=> 'recipient_address',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'delete' 			=> 'deleted',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Email.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_email.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_newsletter_domain_model_link', 'EXT:newsletter/Resources/Private/Language/locallang_csh_tx_newsletter_domain_model_link.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_newsletter_domain_model_link');
$TCA['tx_newsletter_domain_model_link'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_db.xml:tx_newsletter_domain_model_link',
		'label' 			=> 'url',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Link.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newsletter_domain_model_link.gif'
	)
);


// Loads BE modules
if (TYPO3_MODE=="BE")	{
	// temporary line
	t3lib_extMgm::addModule("web","newsletterM2","before:info",t3lib_extMgm::extPath($_EXTKEY)."mod2/");

	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'web',// Make newsletter module a submodule of 'user'
		'tx_newsletter_m1',  // Submodule key
		'before:info',           // Position
		array(
			'Newsletter' => 'index',
			'Statistic' => 'index,show',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:newsletter/Resources/Public/images/icons/ext_icon.png',
			'labels' => 'LLL:EXT:newsletter/Resources/Private/Language/locallang_module.xml',
		)
	);
}
?>