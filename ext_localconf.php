<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
   
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Mailer'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Mailer'] = 'TYPO3 CMS - newsletter extension';
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Precedence'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Precedence'] = 'bulk';
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Provided-by'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Sponsored-by'] = 'http://www.casalogic.dk/ - Open Source Experts.';

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
