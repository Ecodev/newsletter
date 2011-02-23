<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: $
 */

require_once(t3lib_extMgm::extPath('newsletter', 'debug.php'));
return array(
	'tx_newsletter_newslettertask' => t3lib_extMgm::extPath('newsletter', 'class.tx_newsletter_newslettertask.php'),
	'tx_newsletter_newsletterbouncetask' => t3lib_extMgm::extPath('newsletter', 'class.tx_newsletter_newsletterbouncetask.php'),
	'tx_newsletter_domain_repository_bounceaccountrepository' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Repository/BounceAccountRepository.php'),
	'tx_newsletter_domain_repository_emailrepository' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Repository/EmailRepository.php'),
	'tx_newsletter_domain_repository_newsletterrepository' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Repository/NewsletterRepository.php'),
	'tx_newsletter_domain_repository_recipientlistrepository' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Repository/RecipientListRepository.php'),
	'tx_newsletter_domain_repository_abstractrepository' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Repository/AbstractRepository.php'),
	'tx_newsletter_domain_model_bounceaccount' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/BounceAccount.php'),
	'tx_newsletter_domain_model_email' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/Email.php'),
	'tx_newsletter_domain_model_link' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/Link.php'),
	'tx_newsletter_domain_model_newsletter' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/Newsletter.php'),
	'tx_newsletter_domain_model_recipientlist' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList.php'),	
	'tx_newsletter_domain_model_recipientlist_array' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/Array.php'),
	'tx_newsletter_domain_model_recipientlist_beusers' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/BeUsers.php'),
	'tx_newsletter_domain_model_recipientlist_csvfile' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/CsvFile.php'),
	'tx_newsletter_domain_model_recipientlist_csvlist' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/CsvList.php'),
	'tx_newsletter_domain_model_recipientlist_csvurl' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/CsvUrl.php'),
	'tx_newsletter_domain_model_recipientlist_fegroups' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/FeGroups.php'),
	'tx_newsletter_domain_model_recipientlist_fepages' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/FePages.php'),
	'tx_newsletter_domain_model_recipientlist_gentlesql' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/GentleSql.php'),
	'tx_newsletter_domain_model_recipientlist_html' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/Html.php'),
	'tx_newsletter_domain_model_recipientlist_rawsql' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/RawSql.php'),
	'tx_newsletter_domain_model_recipientlist_sql' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/Sql.php'),
	'tx_newsletter_domain_model_recipientlist_ttaddress' => t3lib_extMgm::extPath('newsletter', '/Classes/Domain/Model/RecipientList/TtAddress.php'),
);

