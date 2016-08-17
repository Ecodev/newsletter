<?php

namespace Ecodev\Newsletter\Domain\Repository;

/**
 * Abstract repository to workaround difficulties (or misunderstanding?) with extbase.
 */
abstract class AbstractRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Override parent method to set default settings to ignore storagePid because we did
     * not understand how to use it. And we usually don't want to be tied to a
     * specific pid anyway, so we prefer to do it manually when necessary.
     * TODO this method should be destroyed once we understand how to properly work with storagePid
     */
    public function createQuery()
    {
        $query = parent::createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query;
    }

    /**
     * Update the object immediately in DB. This is used for time-sensitive operation such as locks.
     * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $modifiedObject
     */
    public function updateNow(\TYPO3\CMS\Extbase\DomainObject\AbstractEntity $modifiedObject)
    {
        parent::update($modifiedObject);
        $this->persistenceManager->persistAll();
    }

    /**
     * Override parent method to update the object and persist changes immediately. By commiting immediately
     * stay compatible with raw sql query via $TYPO3_DB.
     * TODO this method should be destroyed once "old code" is completely replaced with extbase concepts
     * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $modifiedObject
     */
    public function update($modifiedObject)
    {
        return $this->updateNow($modifiedObject);
    }
}
