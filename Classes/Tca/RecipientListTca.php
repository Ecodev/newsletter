<?php

namespace Ecodev\Newsletter\Tca;

use GeneralUtility;

/**
 * Handle bounced emails. Fetch them, analyse them and take approriate actions.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
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
        $uid = intval($PA['row']['uid']);
        if ($uid != 0) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
            $recipientListRepository = $objectManager->get('Ecodev\\Newsletter\\Domain\\Repository\\RecipientListRepository');
            $recipientList = $recipientListRepository->findByUidInitialized($uid);

            $result .= $recipientList->getExtract();
        }
        return $result;
    }

}
