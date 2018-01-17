<?php

namespace Ecodev\Newsletter\Domain\Model\RecipientList;

use Ecodev\Newsletter\Tools;

/**
 * Recipient List using Frontend Groups
 */
class FeGroups extends GentleSql
{
    /**
     * feGroups
     *
     * @var string
     */
    protected $feGroups;

    /**
     * Setter for feGroups
     *
     * @param string $feGroups feGroups
     */
    public function setFeGroups($feGroups)
    {
        $this->feGroups = $feGroups;
    }

    /**
     * Getter for feGroups
     *
     * @return string feGroups
     */
    public function getFeGroups()
    {
        return $this->feGroups;
    }

    /**
     * Returns the tablename to work with
     *
     * @return string
     */
    protected function getTableName()
    {
        return 'fe_users';
    }

    public function init()
    {
        $groups = explode(',', $this->getFeGroups());
        $groups[] = -1;
        $groups = array_filter($groups);

        $this->data = Tools::getDatabaseConnection()->sql_query(
            'SELECT DISTINCT email,name,address,telephone,fax,username,fe_users.title,zip,city,country,www,company,fe_groups.title AS group_title
				FROM fe_groups, fe_users
				WHERE fe_groups.uid IN (' . implode(',', $groups) . ")
				AND FIND_IN_SET(fe_groups.uid, fe_users.usergroup)
				AND email != ''
				AND fe_groups.deleted = 0
				AND fe_groups.hidden = 0
				AND fe_users.disable = 0
				AND fe_users.deleted = 0
				AND tx_newsletter_bounce < 10");
    }
}
