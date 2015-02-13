<?php


namespace Ecodev\Newsletter\Domain\Model\RecipientList;

/**
 * Recipient List using Frontend User stored on a given pages
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FePages extends GentleSql
{

    /**
     * fePages
     *
     * @var string $fePages
     */
    protected $fePages;

    /**
     * Setter for fePages
     *
     * @param string $fePages fePages
     * @return void
     */
    public function setFePages($fePages)
    {
        $this->fePages = $fePages;
    }

    /**
     * Getter for fePages
     *
     * @return string fePages
     */
    public function getFePages()
    {
        return $this->fePages;
    }

    /**
     * Returns the tablename to work with
     * @return string
     */
    protected function getTableName()
    {
        return 'fe_users';
    }

    public function init()
    {
        $config = explode(',', $this->getFePages());
        $config[] = -1;
        $config = array_filter($config);

        $this->data = $GLOBALS['TYPO3_DB']->sql_query(
                "SELECT DISTINCT fe_users.uid,name,address,telephone,fax,email,username,fe_users.title,zip,city,country,www,company,pages.title as pages_title
				FROM pages
				INNER JOIN fe_users ON pages.uid = fe_users.pid
				WHERE pages.uid IN (" . implode(',', $config) . ")
				AND email != ''
				AND pages.deleted = 0
				AND pages.hidden = 0
				AND fe_users.disable = 0
				AND fe_users.deleted = 0
				AND tx_newsletter_bounce < 10");
    }
}
