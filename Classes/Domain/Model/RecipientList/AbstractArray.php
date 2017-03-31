<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

use Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * This is the basic class for extracting recipient from other data sources than the database.
 * Here the internal datastructure is an array.
 * You might extend your class from this if you use external sources.
 */
abstract class AbstractArray extends RecipientList
{
    public function getRecipient()
    {
        $r = current($this->data);
        next($this->data);

        if (is_array($r)) {
            if (!isset($r['plain_only'])) {
                $r['plain_only'] = $this->getPlainOnly();
            }

            return $r;
        }

        return false;
    }

    public function getCount()
    {
        return count($this->data);
    }

    public function getError()
    {
        if (!is_array($this->data)) {
            return 'Not an array';
        }

        if (count($this->data) == 0) {
            return 'No data fetched';
        }
    }
}
