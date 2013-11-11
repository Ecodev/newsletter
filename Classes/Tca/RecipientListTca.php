<?php

/**
 * Handle bounced emails. Fetch them, analyse them and take approriate actions.
 *
 * @package Newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Newsletter_Tca_RecipientListTca
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
            $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
            $recipientListRepository = $objectManager->get('Tx_Newsletter_Domain_Repository_RecipientListRepository');
            $recipientList = $recipientListRepository->findByUidInitialized($uid);

            $result .= $recipientList->getExtract();
        }
        return $result;
    }

}
