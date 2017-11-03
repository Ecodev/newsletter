<?php

namespace Ecodev\Newsletter\Domain\Repository;

use Ecodev\Newsletter\Domain\Model\Newsletter;
use Ecodev\Newsletter\Tools;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Repository for \Ecodev\Newsletter\Domain\Model\Newsletter
 */
class NewsletterRepository extends AbstractRepository
{
    /**
     * Returns the latest newsletter for the given page
     * @param int $pid
     */
    public function getLatest($pid)
    {
        $query = $this->createQuery();
        $query->setLimit(1);
        $query->matching($query->equals('pid', $pid));

        $query->setOrderings(['uid' => QueryInterface::ORDER_DESCENDING]);

        return $query->execute()->getFirst();
    }

    /**
     * @param int $pid
     */
    public function findAllByPid($pid)
    {
        if ($pid < 1) {
            return $this->findAll();
        }

        $query = $this->createQuery();
        $query->matching($query->equals('pid', $pid));

        $query->setOrderings(['uid' => QueryInterface::ORDER_DESCENDING]);

        return $query->execute();
    }

    /**
     * Returns all newsletter which are ready to be sent now and not yet locked (sending already started)
     * @return \Ecodev\Newsletter\Domain\Model\Newsletter[]
     */
    public function findAllReadyToSend()
    {
        $query = $this->createQuery();
        $query->matching(
                $query->logicalAnd(
                        $query->lessThanOrEqual('plannedTime', time()), $query->logicalNot($query->equals('plannedTime', 0)), $query->equals('beginTime', 0)
                )
        );

        return $query->execute();
    }

    /**
     * Returns all newsletter which are currently being sent
     * @return \Ecodev\Newsletter\Domain\Model\Newsletter[]
     */
    public function findAllBeingSent()
    {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM `tx_newsletter_domain_model_newsletter` WHERE uid IN (SELECT newsletter FROM `tx_newsletter_domain_model_email` WHERE end_time = 0)');

        return $query->execute()->toArray();
    }

    /**
     * Returns newsletter statistics to be used for pie and timeline chart
     * We will get the full state for each time when something happened
     * @param \Ecodev\Newsletter\Domain\Model\Newsletter $newsletter
     * @return array eg: array(array(time, emailNotSentCount, emailSentCount, emailOpenedCount, emailBouncedCount, emailCount, linkOpenedCount, linkCount, [and same fields but Percentage instead of Count] ))
     */
    public function getStatistics(Newsletter $newsletter)
    {
        $uidNewsletter = $newsletter->getUid();

        $stateDifferences = [];
        $emailCount = $this->fillStateDifferences(
                $stateDifferences, 'tx_newsletter_domain_model_email', 'newsletter = ' . $uidNewsletter, [
            'end_time' => ['increment' => 'emailSentCount', 'decrement' => 'emailNotSentCount'],
            'open_time' => ['increment' => 'emailOpenedCount', 'decrement' => 'emailSentCount'],
            'bounce_time' => ['increment' => 'emailBouncedCount', 'decrement' => 'emailSentCount'],
                ]
        );

        $linkRepository = $this->objectManager->get(LinkRepository::class);
        $linkCount = $linkRepository->getCount($uidNewsletter);
        $this->fillStateDifferences(
                $stateDifferences, 'tx_newsletter_domain_model_link LEFT JOIN tx_newsletter_domain_model_linkopened ON (tx_newsletter_domain_model_linkopened.link = tx_newsletter_domain_model_link.uid)', 'tx_newsletter_domain_model_link.newsletter = ' . $uidNewsletter, [
            'open_time' => ['increment' => 'linkOpenedCount'],
                ]
        );

        // Find out the very first event (when the newsletter was planned)
        $plannedTime = $newsletter ? $newsletter->getPlannedTime() : null;
        $emailCount = $newsletter ? $newsletter->getEmailCount() : $emailCount; // We re-calculate email count so get correct number if newsletter is not sent yet
        $previousState = [
            'time' => $plannedTime ? (int) $plannedTime->format('U') : null,
            'emailNotSentCount' => $emailCount,
            'emailSentCount' => 0,
            'emailOpenedCount' => 0,
            'emailBouncedCount' => 0,
            'emailCount' => $emailCount,
            'linkOpenedCount' => 0,
            'linkCount' => $linkCount,
            'emailNotSentPercentage' => 100,
            'emailSentPercentage' => 0,
            'emailOpenedPercentage' => 0,
            'emailBouncedPercentage' => 0,
            'linkOpenedPercentage' => 0,
        ];

        // Find out what the best grouping step is according to number of states
        $stateCount = count($stateDifferences);
        if ($stateCount > 5000) {
            $groupingTimestep = 15 * 60; // 15 minutes
        } elseif ($stateCount > 500) {
            $groupingTimestep = 5 * 60; // 5 minutes
        } elseif ($stateCount > 50) {
            $groupingTimestep = 1 * 60; // 1 minutes
        } else {
            $groupingTimestep = 0; // no grouping at all
        }

        $states = [$previousState];
        ksort($stateDifferences);
        $minimumTimeToInsert = 0; // First state must always be not grouped, so we don't increment here
        foreach ($stateDifferences as $time => $diff) {
            $newState = $previousState;
            $newState['time'] = $time;

            // Apply diff to previous state to get new state's absolute values
            foreach ($diff as $key => $value) {
                $newState[$key] += $value;
            }

            // Compute percentage for email states
            foreach (['emailNotSent', 'emailSent', 'emailOpened', 'emailBounced'] as $key) {
                $newState[$key . 'Percentage'] = $newState[$key . 'Count'] / $newState['emailCount'] * 100;
            }

            // Compute percentage for link states
            if ($newState['linkCount'] && $newState['emailCount']) {
                $newState['linkOpenedPercentage'] = $newState['linkOpenedCount'] / ($newState['linkCount'] * $newState['emailCount']) * 100;
            } else {
                $newState['linkOpenedPercentage'] = 0;
            }

            // Insert the state only if grouping allows it
            if ($time >= $minimumTimeToInsert) {
                $states[] = $newState;
                $minimumTimeToInsert = $time + $groupingTimestep;
            }
            $previousState = $newState;
        }

        // Don't forget to always add the very last state, if not already inserted
        if (!($time >= $minimumTimeToInsert)) {
            $states[] = $newState;
        }

        return $states;
    }

    /**
     * Fills the $stateDifferences array with incremental difference that the state introduce.
     * It supports merging with existing diff in the array and several states on the same time.
     * @param array $stateDifferences
     * @param string $from
     * @param string $where
     * @param array $stateConfiguration
     * @return int count of records (not count of states)
     */
    protected function fillStateDifferences(array &$stateDifferences, $from, $where, array $stateConfiguration)
    {
        $default = [
            'emailNotSentCount' => 0,
            'emailSentCount' => 0,
            'emailOpenedCount' => 0,
            'emailBouncedCount' => 0,
            'emailBouncedCount' => 0,
            'linkOpenedCount' => 0,
        ];

        /* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
        $db = Tools::getDatabaseConnection();

        $rs = $db->exec_SELECTquery(implode(', ', array_keys($stateConfiguration)), $from, $where);
        $count = 0;
        while ($email = $db->sql_fetch_assoc($rs)) {
            foreach ($stateConfiguration as $stateKey => $stateConf) {
                $time = $email[$stateKey];
                if ($time) {
                    if (!isset($stateDifferences[$time])) {
                        $stateDifferences[$time] = $default;
                    }

                    ++$stateDifferences[$time][$stateConf['increment']];
                    if (isset($stateConf['decrement'])) {
                        --$stateDifferences[$time][$stateConf['decrement']];
                    }
                }
            }
            ++$count;
        }
        $db->sql_free_result($rs);

        return $count;
    }

    /**
     * Find all pairs of newsletter-email UIDs that are should be sent
     *
     * @param Newsletter $newsletter
     * @return array [[newsletter => 12, email => 5], ...]
     */
    public static function findAllNewsletterAndEmailUidToSend(Newsletter $newsletter = null)
    {
        $db = Tools::getDatabaseConnection();

        // Apply limit of emails per round
        $mails_per_round = (int) Tools::confParam('mails_per_round');
        if ($mails_per_round) {
            $limit = ' LIMIT ' . $mails_per_round;
        } else {
            $limit = '';
        }

        // Apply newsletter restriction if any
        if ($newsletter) {
            $newsletterUid = 'AND tx_newsletter_domain_model_newsletter.uid = ' . $newsletter->getUid();
        } else {
            $newsletterUid = '';
        }

        // Find the uid of emails and newsletters that need to be sent
        $rs = $db->sql_query('SELECT tx_newsletter_domain_model_newsletter.uid AS newsletter, tx_newsletter_domain_model_email.uid AS email
						FROM tx_newsletter_domain_model_email
						INNER JOIN tx_newsletter_domain_model_newsletter ON (tx_newsletter_domain_model_email.newsletter = tx_newsletter_domain_model_newsletter.uid)
						WHERE tx_newsletter_domain_model_email.begin_time = 0
                        ' . $newsletterUid . '
						ORDER BY tx_newsletter_domain_model_email.newsletter ' . $limit);

        $result = [];
        while ($record = $db->sql_fetch_assoc($rs)) {
            $result[] = $record;
        }

        return $result;
    }
}
