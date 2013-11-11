<?php

/**
 * Convert HTML to plain text using external lynx program
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_PlainConverter_Lynx implements Tx_Newsletter_Domain_Model_IPlainConverter
{

    private $url = null;

    public function setContent($content, $contentUrl, $baseUrl)
    {
        $this->url = $contentUrl;
    }

    public function getPlainText()
    {
        exec(Tx_Newsletter_Tools::confParam('path_to_lynx') . ' -dump "' . $this->url . '"', $output);
        $plainText = implode("\n", $output);
        return $plainText;
    }

}
