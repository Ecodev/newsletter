<?php

namespace Ecodev\Newsletter\Update;

use Ecodev\Newsletter\Tools;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update for extensions
 */
class Update implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    private $databaseConnection;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Automatically update extension upon installation
     *
     * This does NOT cover the the extension update case, so manual update via
     * Extension Manager is still required
     *
     * @param string $extensionKey
     */
    public function afterExtensionInstall($extensionKey)
    {
        // Only concerned on running auto-updates if it is the newsletter extension that was installed and IS installed.
        if ($extensionKey != 'newsletter' && !ExtensionManagementUtility::isLoaded($extensionKey)) {
            return;
        }

        $this->update();
    }

    /**
     * Execute all necessary updates
     *
     * @return string HTML output informing user of results
     */
    public function update()
    {
        $output = '';
        foreach ($this->getQueries() as $title => $queries) {

            /* @var $transactedResult \Ecodev\Newsletter\Update\TransactionResult */
            $transactedResult = Transaction::transactInnoDBQueries($queries);

            if ($transactedResult->complete()) {
                $count = $transactedResult->getAffectedDataCount();
                $message = sprintf('%1$d records migrated', $count);
                $severity = FlashMessage::OK;
            } else {
                $message = $transactedResult->getErrorMessage();
                $severity = FlashMessage::ERROR;
            }

            $flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity);
            $output .= $this->renderFlashMessage($flashMessage);
        }

        return $output;
    }

    /**
     * Render a FlashMessage across all TYPO3 version supported
     * @param FlashMessage $flashMessage
     * @return string HTML
     */
    private function renderFlashMessage(FlashMessage $flashMessage)
    {
        // From TYPO3 8.0 and higher, we can use getMessageAsMarkup(), but everything older should use render() method
        if (is_callable([$flashMessage, 'getMessageAsMarkup'])) {
            return $flashMessage->getMessageAsMarkup();
        } else {
            return $flashMessage->render();
        }
    }

    /**
     * Return queries to generate the authCode of emails once and for all
     *
     * @return array
     */
    public function getQueries()
    {
        $queries = array_merge(self::getQueriesToMigrateClassPathsInRecords(), self::getQueriesToEncryptOldBounceAccountPasswords(), self::getQueriesToGenerateAuthCode());

        return $queries;
    }

    /**
     * Return queries to generate the authCode of emails once and for all
     *
     * @return string[]
     */
    private function getQueriesToGenerateAuthCode()
    {
        $count = $this->databaseConnection->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'auth_code IS NULL OR auth_code = ""');
        if (!$count) {
            return [];
        }

        return [
            'Generate authCode for existing emails' => [
                'UPDATE tx_newsletter_domain_model_email SET auth_code = MD5(CONCAT(uid, recipient_address)) WHERE auth_code IS NULL OR auth_code = "";',
            ],
        ];
    }

    /**
     * Return queries to migrate old class paths in newsletter records
     *
     * @return string[]
     */
    private function getQueriesToMigrateClassPathsInRecords()
    {
        $count = $this->databaseConnection->exec_SELECTcountRows('*', 'tx_scheduler_task', 'serialized_task_object LIKE "%Tx_Newsletter_%"');
        $count += $this->databaseConnection->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_recipientlist', 'type LIKE "%Tx_Newsletter_%"');
        $count += $this->databaseConnection->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_newsletter', 'plain_converter LIKE "%Tx_Newsletter_%"');

        if (!$count) {
            return [];
        }

        return [
            'Migrate non-namespaced classes to namespaced classes' => [
                "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:29:\"Tx_Newsletter_Task_SendEmails\"', 'O:33:\"Ecodev\\\\Newsletter\\\\Task\\\\SendEmails\"');",
                "UPDATE tx_scheduler_task SET serialized_task_object = REPLACE(serialized_task_object, 'O:31:\"Tx_Newsletter_Task_FetchBounces\"', 'O:35:\"Ecodev\\\\Newsletter\\\\Task\\\\FetchBounces\"');",
                "UPDATE tx_newsletter_domain_model_recipientlist SET type = REPLACE(type, 'Tx_Newsletter_Domain_Model_RecipientList_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\RecipientList\\\\');",
                "UPDATE tx_newsletter_domain_model_newsletter SET plain_converter = REPLACE(plain_converter, 'Tx_Newsletter_Domain_Model_PlainConverter_', 'Ecodev\\\\Newsletter\\\\Domain\\\\Model\\\\PlainConverter\\\\');",
            ],
        ];
    }

    /**
     * Encrypt old bounce account passwords and preserve old default config
     *
     * @return string[]
     */
    private function getQueriesToEncryptOldBounceAccountPasswords()
    {
        // Fetch the old records - they will have a default port and an empty config.
        $rs = $this->databaseConnection->exec_SELECTquery('uid, password', 'tx_newsletter_domain_model_bounceaccount', 'port = 0 AND config = \'\'');
        $records = [];
        while ($record = $this->databaseConnection->sql_fetch_assoc($rs)) {
            $records[] = $record;
        }
        $this->databaseConnection->sql_free_result($rs);

        if (empty($records)) {
            return [];
        }

        // Keep the old config to not break old installations
        $config = Tools::encrypt("poll ###SERVER###\nproto ###PROTOCOL### \nusername \"###USERNAME###\"\npassword \"###PASSWORD###\"\n");
        $queries = [];
        foreach ($records as $record) {
            $queries[] = $this->databaseConnection->UPDATEquery('tx_newsletter_domain_model_bounceaccount', 'uid=' . intval($record['uid']), [
                'password' => Tools::encrypt($record['password']),
                'config' => $config,
            ]);
        }

        return ['Encrypt bounce account passwords' => $queries];
    }
}
