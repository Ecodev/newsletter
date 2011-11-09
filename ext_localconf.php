<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
   
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Mailer'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Mailer'] = 'TYPO3 CMS - newsletter extension';
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Precedence'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Precedence'] = 'bulk';
if (!isset($TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Provided-by'])) $TYPO3_CONF_VARS['EXTCONF']['newsletter']['extraMailHeaders']['X-Sponsored-by'] = 'http://www.casalogic.dk/ - Open Source Experts.';


// Register keys for CLI
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_mailer'] = array('EXT:newsletter/cli/mailer.php', '_CLI_newsletter');
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_mailer_test_only'] = array('EXT:newsletter/cli/mailer_test_only.php', '_CLI_newsletter');
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_spool_create'] = array('EXT:newsletter/cli/spool_create.php', '_CLI_newsletter');
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_spool_run'] = array('EXT:newsletter/cli/spool_run.php', '_CLI_newsletter');
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['newsletter_bounce'] = array('EXT:newsletter/cli/bounce.php', '_CLI_newsletter');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_newsletter_NewsletterTask'] = array(
        'extension'        => $_EXTKEY,
        'title'            => 'Run Newsletter',
        'description'      => 'Send emails',
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_newsletter_NewsletterbounceTask'] = array(
        'extension'        => $_EXTKEY,
        'title'            => 'Run Newsletter Bounce',
        'description'      => 'Fetch bounce statistic',
);
