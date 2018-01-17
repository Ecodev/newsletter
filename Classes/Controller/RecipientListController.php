<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\RecipientListRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller for the RecipientList object
 */
class RecipientListController extends ExtDirectActionController
{
    /**
     * recipientListRepository
     *
     * @var RecipientListRepository
     */
    protected $recipientListRepository;

    /**
     * injectRecipientListRepository
     *
     * @param RecipientListRepository $recipientListRepository
     */
    public function injectRecipientListRepository(RecipientListRepository $recipientListRepository)
    {
        $this->recipientListRepository = $recipientListRepository;
    }

    /**
     * Displays all RecipientLists
     *
     * @return string The rendered list view
     */
    public function listAction()
    {
        $recipientLists = $this->recipientListRepository->findAll();

        // We init recipientLists so we can getCount() on them
        foreach ($recipientLists as $recipientList) {
            $recipientList->init();
        }

        $this->view->setVariablesToRender(['total', 'data', 'success', 'flashMessages']);
        $this->view->setConfiguration([
            'data' => [
                '_descendAll' => self::resolveJsonViewConfiguration(),
            ],
        ]);

        $this->addFlashMessage('Loaded RecipientLists from Server side.', 'RecipientLists loaded successfully', FlashMessage::NOTICE);

        $this->view->assign('total', $recipientLists->count());
        $this->view->assign('data', $recipientLists);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
    }

    /**
     * Returns the list of recipient for the specified recipientList
     *
     * @param int $uidRecipientList
     * @param int $start
     * @param int $limit
     */
    public function listRecipientAction($uidRecipientList, $start, $limit)
    {
        $recipientLists = $this->recipientListRepository->findByUidInitialized($uidRecipientList);

        // Gather recipient according to defined limits
        $i = 0;
        $recipients = [];
        while ($recipient = $recipientLists->getRecipient()) {
            if ($i++ >= $start) {
                $recipients[] = $recipient;
                if (count($recipients) == $limit) {
                    break;
                }
            }
        }

        $metaData = [
            'totalProperty' => 'total',
            'successProperty' => 'success',
            'idProperty' => 'uid',
            'root' => 'data',
            'fields' => [],
        ];

        if (count($recipients)) {
            foreach (array_keys(reset($recipients)) as $field) {
                $metaData['fields'][] = ['name' => $field, 'type' => 'string'];
            }
        }

        $this->addFlashMessage('Loaded Recipients from Server side.', 'Recipients loaded successfully', FlashMessage::NOTICE);

        $this->view->assign('metaData', $metaData);
        $this->view->assign('total', $recipientLists->getCount());
        $this->view->assign('data', $recipients);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
        $this->view->setVariablesToRender(['metaData', 'total', 'data', 'success', 'flashMessages']);
    }

    /**
     * Export a list of recipient and all their data
     *
     * @param int $uidRecipientList
     * @param string $authCode
     */
    public function exportAction($uidRecipientList, $authCode)
    {
        // Assert we are using supported formats
        $availableFormats = ['csv', 'xml'];
        $format = $this->request->getFormat();
        if (!in_array($format, $availableFormats, true)) {
            $format = reset($availableFormats);
            $this->request->setFormat($format);
        }

        $recipientList = $this->recipientListRepository->findByUidInitialized($uidRecipientList);
        if (GeneralUtility::stdAuthCode($recipientList->_getCleanProperties()) != $authCode) {
            $this->response->setStatus(401);

            return 'not authorized !';
        }

        $title = $recipientList->getTitle() . '-' . $recipientList->getUid();

        $this->response->setHeader('Content-Type', 'text/' . $format, true);
        $this->response->setHeader('Content-Description', 'File transfer', true);
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $title . '.' . $format . '"', true);

        $recipients = [];
        while ($recipient = $recipientList->getRecipient()) {
            $recipients[] = $recipient;
        }

        // If we have at least one recipient we can extract the field
        if ($recipients) {
            $fields = array_keys(reset($recipients));
        } else {
            $fields = [];
        }

        $this->view->assign('recipients', $recipients);
        $this->view->assign('title', $title);
        $this->view->assign('fields', $fields);
    }

    /**
     * Returns a configuration for the JsonView, that describes which fields should be rendered for
     * a RecipientList record.
     *
     * @return array
     */
    public static function resolveJsonViewConfiguration()
    {
        return [
            '_exposeObjectIdentifier' => true,
            '_only' => [
                'title',
                'plainOnly',
                'lang',
                'type',
                'count',
            ],
        ];
    }
}
