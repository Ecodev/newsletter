<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Abstract repository to workaround difficulties (or misunderstanding?) with extbase.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class Tx_Newsletter_Domain_Repository_AbstractRepository extends Tx_Extbase_Persistence_Repository
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
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        return $query;
    }

    /**
     * Update the object immediately in DB. This is used for time-sensitive operation such as locks.
     * @param object $modifiedObject
     */
    public function updateNow($modifiedObject)
    {
        parent::update($modifiedObject);
        $this->persistenceManager->persistAll();
    }

    /**
     * Override parent method to update the object and persist changes immediately. By commiting immediately
     * stay compatible with raw sql query via $TYPO3_DB.
     * TODO this method should be destroyed once "old code" is completely replaced with extbase concepts
     * @param object $modifiedObject
     */
    public function update($modifiedObject)
    {
        return $this->updateNow($modifiedObject);
    }

}
