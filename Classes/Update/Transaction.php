<?php
namespace Ecodev\Newsletter\Update;

/*
 * *************************************************************
 * Copyright notice
 *
 * (c) 2015
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************
 */

/**
 * A collection of transacted operations.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author marvin-martian https://github.com/marvin-martian
 */
class Transaction
{

    /**
     * Executes an array of of INNODB queries wrapped in a transaction.
     * WARNING: Only works with InnoDB tables and DOES NOT CHECK the table type!!!
     * I am assuming you know what queries you are throwing into this method.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param array $queries
     *            An array of SQL queries.
     * @return \Ecodev\Newsletter\Update\TransactionResult
     */
    public static function transactInnoDBQueries(array $queries)
    {
        $results = new TransactionResult(count($queries));
        if (! empty($queries)) {
            global $TYPO3_DB;
            // By wrapping the queries in a transaction we can rollback if
            // something goes wrong and not destroy the users data in the process.
            // WARNING: Only works with InnoDB tables and DOES NOT CHECK the table type!!!
            $TYPO3_DB->sql_query("START TRANSACTION;");
            $results = self::transactDBQueries($queries);
            if ($results->getErrorMessage()) {
                $TYPO3_DB->sql_query("ROLLBACK;");
                // Because we rolled back nothing was modified so we can safely reset the integrity state.
                $results->resetDataIntegrity();
                return $results;
            }
            $TYPO3_DB->sql_query("COMMIT;");
        }
        return $results;
    }

    /**
     * Executes an array of database queries.
     *
     * @global \TYPO3\CMS\Core\Database\DatabaseConnection $TYPO3_DB
     * @param array $queries
     *            An array of SQL queries.
     * @return \Ecodev\Newsletter\Update\TransactionResult
     */
    public static function transactDBQueries(array $queries)
    {
        $results = new TransactionResult(count($queries));
        if (! empty($queries)) {
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

    /**
     * Transacts a list of file operations, on encountering an error ALL operations are rolled back to their previous state (if possible).
     * 
     * @param array $operands            
     * @return \Ecodev\Newsletter\Update\TransactionResult
     */
    public static function transactFileOperands(array $operands)
    {
        $results = new TransactionResult(count($operands));
        if (! empty($operands)) {
            // @todo transactFileOperands
        }
        return $results;
    }
}