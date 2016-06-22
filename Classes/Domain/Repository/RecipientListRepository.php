<?php

namespace Ecodev\Newsletter\Domain\Repository;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * A repository for RecipientList
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
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
