<?php

namespace Ecodev\Newsletter\Domain\Repository;

use Ecodev\Newsletter\Domain\Model\BounceAccount;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Repository for \Ecodev\Newsletter\Domain\Model\BounceAccount
 */
class BounceAccountRepository extends AbstractRepository
{
    public function createQuery()
    {
        $query = parent::createQuery();

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        $storagePid = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'newsletter', 'storagePid');

        if ($storagePid['storagePid']) {
            $query->getQuerySettings()->setRespectStoragePage(true);
        }

        return $query;
    }

    /**
     * Returns the first BounceAccount or null if none at all
     *
     * @return BounceAccount
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
