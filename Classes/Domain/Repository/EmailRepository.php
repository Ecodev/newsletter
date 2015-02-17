<?php


namespace Ecodev\Newsletter\Domain\Repository;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Repository for \Ecodev\Newsletter\Domain\Model\Email
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EmailRepository extends AbstractRepository
{

    protected static $emailCountCache = array();

    /**
     * Returns the email corresponsding to the authCode
     * @param string $authcode
     * @return \Ecodev\Newsletter\Domain\Model\Email
     */
    public function findByAuthcode($authcode)
    {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM `tx_newsletter_domain_model_email` WHERE MD5(CONCAT(`uid`, `recipient_address`)) = ? LIMIT 1', array($authcode));

        return $query->execute()->getFirst();
    }

    /**
     * Returns the count of emails for a given newsletter
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param integer $uidNewsletter
     */
    public function getCount($uidNewsletter)
    {
        // If we have cached result return directly that value to avoid X query for X Links per newsletter
        if (isset(self::$emailCountCache[$uidNewsletter])) {
            return self::$emailCountCache[$uidNewsletter];
        }

        global $TYPO3_DB;
        $count = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_email', 'newsletter = ' . $uidNewsletter);
        self::$emailCountCache[$uidNewsletter] = $count;

        return (int) $count;
    }

    /**
     * Returns all email for a given newsletter
     * @param integer $uidNewsletter
     * @param integer $start
     * @param integer $limit
     * @return \Ecodev\Newsletter\Domain\Model\Email[]
     */
    public function findAllByNewsletter($uidNewsletter, $start, $limit)
    {
        if ($uidNewsletter < 1) {
            return $this->findAll();
        }

        $query = $this->createQuery();
        $query->matching($query->equals('newsletter', $uidNewsletter));
        $query->setLimit($limit);
        $query->setOffset($start);

        return $query->execute();
    }

    /**
     * Register an open email in database and forward the event to RecipientList
     * so it can optionnally do something more
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param string $authCode
     */
    public function registerOpen($authCode)
    {
        global $TYPO3_DB;

        // Minimal sanitization before SQL
        $authCode = addslashes($authCode);

        $TYPO3_DB->sql_query("UPDATE tx_newsletter_domain_model_email SET open_time = " . time() . " WHERE open_time = 0 AND MD5(CONCAT(uid, recipient_address)) = '$authCode' LIMIT 1");

        // Tell the target that he opened the email
        $rs = $TYPO3_DB->sql_query("
		SELECT tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.recipient_address
		FROM tx_newsletter_domain_model_email
		LEFT JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
		LEFT JOIN tx_newsletter_domain_model_recipientlist ON (tx_newsletter_domain_model_newsletter.recipient_list = tx_newsletter_domain_model_recipientlist.uid)
		WHERE MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)) = '$authCode' AND recipient_list IS NOT NULL
		LIMIT 1");

        if (list($recipientListUid, $emailAddress) = $TYPO3_DB->sql_fetch_row($rs)) {
            $recipientListRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository');
            $recipientList = $recipientListRepository->findByUid($recipientListUid);
            if ($recipientList) {
                $recipientList->registerOpen($emailAddress);
            }
        }
    }
}
