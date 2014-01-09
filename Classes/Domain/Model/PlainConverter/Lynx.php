<?php

/**
 * Convert HTML to plain text using external lynx program
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Domain_Model_PlainConverter_Lynx implements Tx_Newsletter_Domain_Model_IPlainConverter
{

    public function getPlainText($content, $baseUrl)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'html');
        file_put_contents($tmpFile, $content);

        $cmd = escapeshellcmd(Tx_Newsletter_Tools::confParam('path_to_lynx')) . ' -dump -stdin < ' . escapeshellarg($tmpFile);
        exec($cmd, $output);
        unlink($tmpFile);
        $plainText = implode("\n", $output);

        return $plainText;
    }

}
