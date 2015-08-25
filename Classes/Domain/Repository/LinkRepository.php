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
 * Repository for \Ecodev\Newsletter\Domain\Model\Link
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LinkRepository extends AbstractRepository
{
    /**
     * Returns all links for a given newsletter
     * @param integer $uidNewsletter
     * @param integer $start
     * @param integer $limit
     * @return \Ecodev\Newsletter\Domain\Model\Link[]
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
     * Returns the count of links for a given newsletter
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param integer $uidNewsletter
     */
    public function getCount($uidNewsletter)
    {
        global $TYPO3_DB;
        $count = $TYPO3_DB->exec_SELECTcountRows('*', 'tx_newsletter_domain_model_link', 'newsletter = ' . $uidNewsletter);

        return (int) $count;
    }

    /**
     * Register a clicked link in database and forward the event to RecipientList
     * so it can optionnally do something more
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param integer|null $newsletterUid newsletter UID to limit search scope, or NULL
     * @param string $authCode identifier to find back the link
     * @param boolean $isPlain
     * @return string|null absolute URL to be redirected to
     */
    public function registerClick($newsletterUid, $authCode, $isPlain)
    {
        global $TYPO3_DB;

        // Minimal sanitization before SQL
        $authCode = $TYPO3_DB->fullQuoteStr($authCode, 'tx_newsletter_domain_model_link');
        $isPlain = $isPlain ? '1' : '0';
        if ($newsletterUid) {
            $limitNewsletter = 'AND tx_newsletter_domain_model_newsletter.uid = ' . (int) $newsletterUid;
        } else {
            $limitNewsletter = '';
        }

        // Attempt to find back records in database based on given authCode
        $rs = $TYPO3_DB->sql_query("SELECT tx_newsletter_domain_model_link.uid, tx_newsletter_domain_model_link.url, tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_newsletter.recipient_list, tx_newsletter_domain_model_email.recipient_address
        FROM tx_newsletter_domain_model_newsletter
		INNER JOIN tx_newsletter_domain_model_email ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
		INNER JOIN tx_newsletter_domain_model_link ON (tx_newsletter_domain_model_link.newsletter = tx_newsletter_domain_model_newsletter.uid)
		WHERE
		MD5(CONCAT(MD5(CONCAT(tx_newsletter_domain_model_email.uid, tx_newsletter_domain_model_email.recipient_address)), tx_newsletter_domain_model_link.uid)) = $authCode
        $limitNewsletter");

        if (list($linkUid, $linkUrl, $emailUid, $recipientListUid, $email) = $TYPO3_DB->sql_fetch_row($rs)) {

            // Insert a linkopened record to register which user clicked on which link
            $TYPO3_DB->sql_query("
            INSERT INTO tx_newsletter_domain_model_linkopened (link, email, is_plain, open_time)
            VALUES ($linkUid, $emailUid, $isPlain, " . time() . ")
            ");

            // Increment the total count of clicks for the link itself (so if the linkopened records are deleted, we still know how many times the link was opened)
            $TYPO3_DB->sql_query("
            UPDATE tx_newsletter_domain_model_link
            SET tx_newsletter_domain_model_link.opened_count = tx_newsletter_domain_model_link.opened_count + 1
            WHERE
            tx_newsletter_domain_model_link.uid = $linkUid
            ");

            // Also register the email as opened, just in case if it was not already marked open by the open spy (eg: because end-user did not show image)
            $autCodeEmail = md5($emailUid . $email);
            $emailRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\EmailRepository');
            $emailRepository->registerOpen($autCodeEmail);

            // Forward which user clicked the link to the recipientList so the recipientList may take appropriate action
            $recipientListRepository = $this->objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository');
            $recipientList = $recipientListRepository->findByUid($recipientListUid);
            if ($recipientList) {
                $recipientList->registerClick($email);
            }

            return $linkUrl;
        }
    }
}
