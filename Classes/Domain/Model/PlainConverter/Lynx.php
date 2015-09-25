<?php

namespace Ecodev\Newsletter\Domain\Model\PlainConverter;

use Ecodev\Newsletter\Domain\Model\IPlainConverter;
use Ecodev\Newsletter\Tools;

/**
 * Convert HTML to plain text using external lynx program
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Lynx implements IPlainConverter
{
    private function injectBaseUrl($content, $baseUrl)
    {
        if (strpos($content, '<base ') === false) {
            $content = str_ireplace('<body', '<base href="' . $baseUrl . '"><body', $content);
        }

        return $content;
    }

    public function getPlainText($content, $baseUrl)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'newsletter_');
        $contentWithBase = $this->injectBaseUrl($content, $baseUrl);

        file_put_contents($tmpFile, $contentWithBase);

        $cmd = escapeshellcmd(Tools::confParam('path_to_lynx')) . ' -force_html -dump ' . escapeshellarg($tmpFile);
        exec($cmd, $output);
        unlink($tmpFile);
        $plainText = implode("\n", $output);

        return $plainText;
    }
}
