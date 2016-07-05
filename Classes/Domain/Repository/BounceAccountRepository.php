<?php

namespace Ecodev\Newsletter\Domain\Repository;

/**
 * Repository for \Ecodev\Newsletter\Domain\Model\BounceAccount
 */
class BounceAccountRepository extends AbstractRepository
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
     * Returns the first BounceAccount or null if none at all
     * @return type
     */
    public function findFirst()
    {
        $query = $this->createQuery();

        $bounceAccount = $query->setLimit(1)
                ->execute()
                ->getFirst();

        return $bounceAccount;
    }
}
