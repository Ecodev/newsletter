<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

use Ecodev\Newsletter\Tools;

/**
 * Recipient List using Backend Users
 */
class BeUsers extends GentleSql
{
    /**
     * beUsers
     *
     * @var string
     */
    protected $beUsers;

    /**
     * Setter for beUsers
     *
     * @param string $beUsers beUsers
     */
    public function setBeUsers($beUsers)
    {
        $this->beUsers = $beUsers;
    }

    /**
     * Getter for beUsers
     *
     * @return string beUsers
     */
    public function getBeUsers()
    {
        return $this->beUsers;
    }

    /**
     * Returns the tablename to work with
     *
     * @return string
     */
    protected function getTableName()
    {
        return 'be_users';
    }

    public function init()
    {
        $config = explode(',', $this->getBeUsers());
        $config[] = -1;
        $config = array_filter($config);

        $this->data = Tools::getDatabaseConnection()->sql_query(
            'SELECT email, realName, username, lang, admin FROM be_users
				WHERE uid IN (' . implode(',', $config) . ")
				AND email <> ''
				AND disable = 0
				AND tx_newsletter_bounce < 10");
    }
}
