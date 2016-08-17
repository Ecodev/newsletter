<?php

namespace Ecodev\Newsletter\Update;

/**
 * A collection of transacted operations.
 */
class Transaction
{
    /**
     * Executes an array of of INNODB queries wrapped in a transaction.
     * WARNING: Only works with InnoDB tables and DOES NOT CHECK the table type!!!
     * I am assuming you know what queries you are throwing into this method.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param string[] $queries
     *            An array of SQL queries.
     * @return \Ecodev\Newsletter\Update\TransactionResult
     */
    public static function transactInnoDBQueries(array $queries)
    {
        $results = new TransactionResult(count($queries));
        if (!empty($queries)) {
            global $TYPO3_DB;
            // By wrapping the queries in a transaction we can rollback if
            // something goes wrong and not destroy the users data in the process.
            // WARNING: Only works with InnoDB tables and DOES NOT CHECK the table type!!!
            $TYPO3_DB->sql_query('START TRANSACTION;');
            $results = self::transactDBQueries($queries);
            if ($results->getErrorMessage()) {
                $TYPO3_DB->sql_query('ROLLBACK;');
                // Because we rolled back nothing was modified so we can safely reset the integrity state.
                $results->resetDataIntegrity();

                return $results;
            }
            $TYPO3_DB->sql_query('COMMIT;');
        }

        return $results;
    }

    /**
     * Executes an array of database queries.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param string[] $queries
     *            An array of SQL queries.
     * @return \Ecodev\Newsletter\Update\TransactionResult
     */
    private static function transactDBQueries(array $queries)
    {
        $results = new TransactionResult(count($queries));
        if (!empty($queries)) {
            global $TYPO3_DB;
            foreach ($queries as $query) {
                $res = $TYPO3_DB->sql_query($query);
                $results->appendAffectedDataCount($TYPO3_DB->sql_affected_rows($res));
                $error = $TYPO3_DB->sql_error();
                if ($error) {
                    $results->setErrorMessage($error);
                    break;
                }
                $results->stepProcessed();
            }
            $TYPO3_DB->sql_free_result($res);
        }

        return $results;
    }
}
