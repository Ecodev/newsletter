<?php

namespace Ecodev\Newsletter\Domain\Model\PlainConverter;

use Ecodev\Newsletter\Domain\Model\IPlainConverter;

require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newsletter') . '/3dparty/Html2Text.php';

/**
 * Convert HTML to plain text using builtin Html2Text tool
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Builtin implements IPlainConverter
{
    public function getPlainText($content, $baseUrl)
    {
        $converter = new \Html2Text\Html2Text($content, array(
            'do_links' => 'table',
        ));
        $converter->setBaseUrl($baseUrl);

        return $converter->getText();
    }
}
