<?php

require_once(t3lib_extMgm::extPath('newsletter') . '/3dparty/class.html2text.inc');

/**
 * Convert HTML to plain text using builtin html2text tool
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_PlainConverter_Builtin extends html2text implements Tx_Newsletter_Domain_Model_IPlainConverter
{

    private $links = array();

    public function __construct()
    {
        /**
         * Replace Unknown/unhandled entities regexp, with a stricter version: only replace if the unknown entities is made of either only aplha, or only digits
         * This allows '&' in URL followed by inline CSS such as:
         *   <a href="http://www.broken.com/">http://www.broken.com/?a=1&b=2</a><div style="border-top:1px solid #00579F;margin-top:10px;"></div>
         */
        $key = array_search('/&[^&;]+;/i', $this->search);
        if ($key) {
            $this->search[$key] = '/&([[:alpha:]]+|[[:digit:]]+);/i';
        }
    }

    public function getPlainText($content, $baseUrl)
    {
        $this->set_base_url($baseUrl);
        $this->set_html($content);

        return $this->get_text();
    }

    function _convert()
    {
        $this->links = array();
        return parent::_convert();
    }

    /**
     * Override parent function to make links unique
     * @see html2text::_build_link_list()
     */
    function _build_link_list($link, $display)
    {
        // If links already exists, return its existing reference
        if (array_key_exists($link, $this->links)) {
            return $display . ' [' . $this->links[$link] . ']';
        }
        // Otherwise call the parent and memorize returned reference
        else {
            $result = parent::_build_link_list($link, $display);

            preg_match('/\[(\d+)\]$/', $result, $m);
            $reference = $m[1];

            $this->links[$link] = $reference;
            return $result;
        }
    }

}
