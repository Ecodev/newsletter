<?php

namespace Ecodev\Newsletter\Domain\Repository;

/**
 * A repository for RecipientList
 */
class RecipientListRepository extends AbstractRepository
{
    public function createQuery()
    {
        $query = parent::createQuery();

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $configurationManager = $objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
        $storagePid = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'newsletter', 'storagePid');

        if ($storagePid['storagePid']) {
            $query->getQuerySettings()->setRespectStoragePage(true);
        }

        return $query;
    }

    /**
     * Returns a RecipientList already initialized, even if it is hidden
     * @param mixed $uidRecipientlist
     * @return \Ecodev\Newsletter\Domain\Model\RecipientList
     */
    public function findByUidInitialized($uidRecipientlist)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIgnoreEnableFields(true); // because of this line hidden objects can be retrieved
        $recipientList = $query->matching(
                        $query->equals('uid', $uidRecipientlist)
                )
                ->execute()
                ->getFirst();

        if ($recipientList) {
            $recipientList->init();
        }

        return $recipientList;
    }
}
