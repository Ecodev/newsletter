<?php
/**
 * This is the click link script that identifies and registers the user, and provides the correct link
 */
require ('browserrun.php');

$authcode = addslashes(@$_REQUEST['l']);
$isPlain = @$_REQUEST['p'] ? '1' : '0';
$url = @$_REQUEST['url'];

// Insert an email-link record to register which user clicked on which link
$TYPO3_DB->sql_query("
INSERT INTO tx_newsletter_domain_model_linkopened (link, email, is_plain)
SELECT tx_newsletter_domain_model_link.uid AS link, tx_newsletter_domain_model_email.uid AS email, $isPlain AS is_plain 
FROM tx_newsletter_domain_model_email
LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
LEFT JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
WHERE
MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = '$authcode'
");

// Increment the total count of clicks for the link opened (so if the emails record are deleted, we still know how many times the link was opened)
$TYPO3_DB->sql_query("
UPDATE tx_newsletter_domain_model_email
LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
LEFT JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
SET tx_newsletter_domain_model_link.opened_count = tx_newsletter_domain_model_link.opened_count + 1 
WHERE
MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = '$authcode'
");


// Forward which user clicked the link to the recipientList so the recipientList may take appropriate action
$rs = $TYPO3_DB->sql_query("
SELECT tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.recipient_address
FROM tx_newsletter_domain_model_email
LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
LEFT JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
WHERE
MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = '$authcode'
AND recipient_list IS NOT NULL
");
if (list($recipientListUid, $email) = $TYPO3_DB->sql_fetch_row($rs)) {
	$target = Tx_Newsletter_Domain_Model_RecipientList::getTarget($recipientListUid);
	if ($target)
	{
		$target->registerClick($email);
	}
}

// Finally redirect to the destination URL
header("Location: $url");

