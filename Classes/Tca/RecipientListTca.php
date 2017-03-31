<?php

namespace Ecodev\Newsletter\Tca;

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
     */
    public function render($PA, $fObj)
    {
        $result = '';
        $uid = (int) $PA['row']['uid'];
        if ($uid != 0) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            $recipientListRepository = $objectManager->get(\Ecodev\Newsletter\Domain\Repository\RecipientListRepository::class);
            $recipientList = $recipientListRepository->findByUidInitialized($uid);

            $result .= $recipientList->getExtract();
        }

        return $result;
    }
}
