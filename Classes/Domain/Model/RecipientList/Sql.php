<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

use Ecodev\Newsletter\BounceHandler;
use Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * This is the basic SQL related newsletter target. Methods implemented with DB calls using SQL query defined by end-user.
 * Extend this class to create newsletter targets which extracts recipients from the database.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Sql extends RecipientList
{
    /**
     * sqlStatement
     *
     * @var string
     */
    protected $sqlStatement = '';

    /**
     * sqlRegisterBounce
     *
     * @var string
     */
    protected $sqlRegisterBounce = '';

    /**
     * sqlRegisterOpen
     *
     * @var string
     */
    protected $sqlRegisterOpen = '';

    /**
     * sqlRegisterClick
     *
     * @var string
     */
    protected $sqlRegisterClick = '';

    /**
     * Setter for sqlStatement
     *
     * @param string $sqlStatement sqlStatement
     */
    public function setSqlStatement($sqlStatement)
    {
        $this->sqlStatement = $sqlStatement;
    }

    /**
     * Getter for sqlStatement
     *
     * @return string sqlStatement
     */
    public function getSqlStatement()
    {
        return $this->sqlStatement;
    }

    /**
     * Setter for sqlRegisterBounce
     *
     * @param string $sqlRegisterBounce sqlRegisterBounce
     */
    public function setSqlRegisterBounce($sqlRegisterBounce)
    {
        $this->sqlRegisterBounce = $sqlRegisterBounce;
    }

    /**
     * Getter for sqlRegisterBounce
     *
     * @return string sqlRegisterBounce
     */
    public function getSqlRegisterBounce()
    {
        return $this->sqlRegisterBounce;
    }

    /**
     * Setter for sqlRegisterOpen
     *
     * @param string $sqlRegisterOpen sqlRegisterOpen
     */
    public function setSqlRegisterOpen($sqlRegisterOpen)
    {
        $this->sqlRegisterOpen = $sqlRegisterOpen;
    }

    /**
     * Getter for sqlRegisterOpen
     *
     * @return string sqlRegisterOpen
     */
    public function getSqlRegisterOpen()
    {
        return $this->sqlRegisterOpen;
    }

    /**
     * Setter for sqlRegisterClick
     *
     * @param string $sqlRegisterClick sqlRegisterClick
     */
    public function setSqlRegisterClick($sqlRegisterClick)
    {
        $this->sqlRegisterClick = $sqlRegisterClick;
    }

    /**
     * Getter for sqlRegisterClick
     *
     * @return string sqlRegisterClick
     */
    public function getSqlRegisterClick()
    {
        return $this->sqlRegisterClick;
    }

    public function init()
    {
        $sql = trim($this->getSqlStatement());

        // Inject dummy SQL statement, just for fun !
        if (!$sql) {
            $sql = 'SELECT email FROM be_users WHERE 1 = 0';
        }

        $this->data = $GLOBALS['TYPO3_DB']->sql_query($sql);
    }

    /**
     * Fetch a recipient from the sql-record set. This also computes some commonly used values,
     * such as plain_only and language.
     *
     * @return	array	Recipient with user data.
     */
    public function getRecipient()
    {
        $r = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->data);
        if (is_array($r)) {
            if (!isset($r['plain_only'])) {
                $r['plain_only'] = $this->isPlainOnly();
            }

            if (!isset($r['L'])) {
                $r['L'] = $this->getLang();
            }

            return $r;
        } else {
            return false;
        }
    }

    public function getCount()
    {
        return $GLOBALS['TYPO3_DB']->sql_num_rows($this->data);
    }

    public function getError()
    {
        return $GLOBALS['TYPO3_DB']->sql_error($this->data);
    }

    /**
     * Execute the SQL defined by the user to disable a recipient.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param string $email the email address of the recipient
     * @param int $bounceLevel Level of bounce, @see \Ecodev\Newsletter\BounceHandler for possible values
     * @return bool Status of the success of the removal.
     */
    public function registerBounce($email, $bounceLevel)
    {
        global $TYPO3_DB;

        $sql = str_replace([
            '###EMAIL###',
            '###BOUNCE_TYPE###',
            '###BOUNCE_TYPE_SOFT###',
            '###BOUNCE_TYPE_HARD###',
            '###BOUNCE_TYPE_UNSUBSCRIBE###',
                ], [
            $TYPO3_DB->fullQuoteStr($email, 'tx_newsletter_domain_model_recipientlist'), // Here we assume the SQL table to recipientList, but it could be something different.
            $bounceLevel,
            BounceHandler::NEWSLETTER_SOFTBOUNCE,
            BounceHandler::NEWSLETTER_HARDBOUNCE,
            BounceHandler::NEWSLETTER_UNSUBSCRIBE,
                ], $this->getSqlRegisterBounce());

        if ($sql) {
            $TYPO3_DB->sql_query($sql);

            return $TYPO3_DB->sql_affected_rows();
        } else {
            return false;
        }
    }

    /**
     * Execute the SQL defined by the user to register that the email was open
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param string $email the email address of the recipient (who opened the mail)
     */
    public function registerOpen($email)
    {
        global $TYPO3_DB;

        $sql = str_replace('###EMAIL###', $TYPO3_DB->fullQuoteStr($email, 'tx_newsletter_domain_model_recipientlist'), $this->getSqlRegisterOpen());

        if ($sql) {
            $TYPO3_DB->sql_query($sql);

            return $TYPO3_DB->sql_affected_rows();
        } else {
            return false;
        }
    }

    /**
     * Execute the SQL defined by the user to whenever the recipient has clicked a link via click.php
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param string $email the email address of the recipient
     */
    public function registerClick($email)
    {
        global $TYPO3_DB;

        $sql = str_replace('###EMAIL###', $TYPO3_DB->fullQuoteStr($email, 'tx_newsletter_domain_model_recipientlist'), $this->getSqlRegisterClick());

        if ($sql) {
            $TYPO3_DB->sql_query($sql);

            return $TYPO3_DB->sql_affected_rows();
        } else {
            return false;
        }
    }
}
