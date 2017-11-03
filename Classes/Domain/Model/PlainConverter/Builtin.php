<?php

namespace Ecodev\Newsletter\Domain\Model\PlainConverter;

use Ecodev\Newsletter\Domain\Model\IPlainConverter;
use Html2Text\Html2Text;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

require_once ExtensionManagementUtility::extPath('newsletter') . '/3dparty/Html2Text.php';

/**
 * Convert HTML to plain text using builtin Html2Text tool
 */
class Builtin implements IPlainConverter
{
    public function getPlainText($content, $baseUrl)
    {
        $converter = new Html2Text($content, [
            'do_links' => 'table',
        ]);
        $converter->setBaseUrl($baseUrl);

        return $converter->getText();
    }
}
