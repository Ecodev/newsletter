<?php

namespace Ecodev\Newsletter\Tca;

use Ecodev\Newsletter\Domain\Repository\RecipientListRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Render extract of recipient list
 */
class RecipientListTca
{
    /**
     * Returns an HTML table showing recipient_data content
     *
     * @param $PA
     * @param $fObj
     *
     * @return string
     */
    public function render($PA, $fObj)
    {
        $result = '';
        $uid = (int) $PA['row']['uid'];
        if ($uid != 0) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $recipientListRepository = $objectManager->get(RecipientListRepository::class);
            $recipientList = $recipientListRepository->findByUidInitialized($uid);

            $result .= $recipientList->getExtract();
        }

        return $result;
    }
}
