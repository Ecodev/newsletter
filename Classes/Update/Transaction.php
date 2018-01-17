<?php

namespace Ecodev\Newsletter\Update;

use Ecodev\Newsletter\Tools;

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
     * @param string[] $queries
     *            An array of SQL queries
     *
     * @return TransactionResult
     */
    public static function transactInnoDBQueries(array $queries)
    {
        $results = new TransactionResult(count($queries));
        if (!empty($queries)) {
            $db = Tools::getDatabaseConnection();
            // By wrapping the queries in a transaction we can rollback if
            // something goes wrong and not destroy the users data in the process.
            // WARNING: Only works with InnoDB tables and DOES NOT CHECK the table type!!!
            $db->sql_query('START TRANSACTION;');
            $results = self::transactDBQueries($queries);
            if ($results->getErrorMessage()) {
                $db->sql_query('ROLLBACK;');
                // Because we rolled back nothing was modified so we can safely reset the integrity state.
                $results->resetDataIntegrity();

                return $results;
            }
            $db->sql_query('COMMIT;');
        }

        return $results;
    }

    /**
     * Executes an array of database queries.
     *
     * @param string[] $queries
     *            An array of SQL queries
     *
     * @return TransactionResult
     */
    private static function transactDBQueries(array $queries)
    {
        $results = new TransactionResult(count($queries));
        if (!empty($queries)) {
            $db = Tools::getDatabaseConnection();
            foreach ($queries as $query) {
                $res = $db->sql_query($query);
                $results->appendAffectedDataCount($db->sql_affected_rows($res));
                $error = $db->sql_error();
                if ($error) {
                    $results->setErrorMessage($error);
                    break;
                }
                $results->stepProcessed();
            }
            $db->sql_free_result($res);
        }

        return $results;
    }
}
