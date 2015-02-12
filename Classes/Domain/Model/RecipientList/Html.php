<?php


namespace Ecodev\Newsletter\Domain\Model\RecipientList;

use Ecodev\Newsletter\Domain\Model\RecipientList\AbstractArray;
use GeneralUtility;



/**
 * Recipient List using any public URL to fetch and parse for emails addresses
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Html extends AbstractArray
{

    /**
     * htmlUrl
     *
     * @var string $htmlUrl
     */
    protected $htmlUrl;

    /**
     * htmlFetchType
     *
     * @var string $htmlFetchType
     */
    protected $htmlFetchType;

    /**
     * Setter for htmlUrl
     *
     * @param string $htmlUrl htmlUrl
     * @return void
     */
    public function setHtmlUrl($htmlUrl)
    {
        $this->htmlUrl = $htmlUrl;
    }

    /**
     * Getter for htmlUrl
     *
     * @return string htmlUrl
     */
    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }

    /**
     * Setter for htmlFetchType
     *
     * @param string $htmlFetchType htmlFetchType
     * @return void
     */
    public function setHtmlFetchType($htmlFetchType)
    {
        $this->htmlFetchType = $htmlFetchType;
    }

    /**
     * Getter for htmlFetchType
     *
     * @return string htmlFetchType
     */
    public function getHtmlFetchType()
    {
        return $this->htmlFetchType;
    }

    function init()
    {
        $this->data = array();

        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($this->getHtmlUrl());

        switch ($this->getHtmlFetchType()) {
            case 'mailto':
                preg_match_all('|<a[^>]+href="mailto:([^"]+)"[^>]*>(.*)</a>|Ui', $content, $fetched_data);

                foreach ($fetched_data[1] as $i => $email) {
                    $this->data[] = array('email' => $email, 'name' => $fetched_data[2][$i]);
                }
                break;

            case 'regex':
            default:
                preg_match_all("|[\.a-z0-9!#$%&'*+-/=?^_`{\|}]+@[a-z0-9_-][\.a-z0-9_-]*\.[a-z]{2,}|i", $content, $fetched_data);

                foreach ($fetched_data[0] as $address) {
                    $this->data[]['email'] = $address;
                }
        }
    }

}
