<?php

namespace Ecodev\Newsletter\Controller;

use Ecodev\Newsletter\Domain\Repository\LinkRepository;
use Ecodev\Newsletter\MVC\Controller\ExtDirectActionController;

/**
 * Controller for the Link object
 */
class LinkController extends ExtDirectActionController
{
    /**
     * linkRepository
     *
     * @var Ecodev\Newsletter\Domain\Repository\LinkRepository
     */
    protected $linkRepository;

    /**
     * injectLinkRepository
     * @param Ecodev\Newsletter\Domain\Repository\LinkRepository $linkRepository
     */
    public function injectLinkRepository(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * Displays all Links
     *
     * @param int $uidNewsletter
     * @param int $start
     * @param int $limit
     * @return string The rendered list view
     */
    public function listAction($uidNewsletter, $start, $limit)
    {
        $links = $this->linkRepository->findAllByNewsletter($uidNewsletter, $start, $limit);

        $this->view->setVariablesToRender(['total', 'data', 'success', 'flashMessages']);
        $this->view->setConfiguration([
            'data' => [
                '_descendAll' => self::resolveJsonViewConfiguration(),
            ],
        ]);

        $this->addFlashMessage('Loaded all Links from Server side.', 'Links loaded successfully', \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE);

        $this->view->assign('total', $this->linkRepository->getCount($uidNewsletter));
        $this->view->assign('data', $links);
        $this->view->assign('success', true);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush());
    }

    /**
     * Register when a link was clicked and redirect to link's URL.
     * For this method we don't use extbase parameters system to have an URL as short as possible
     */
    public function clickedAction()
    {
        $args = $this->request->getArguments();

        // For compatibility with old links
        $oldArgs = ['n', 'l', 'p'];
        foreach ($oldArgs as $arg) {
            if (!isset($args[$arg])) {
                if (isset($_REQUEST[$arg])) {
                    $args[$arg] = $_REQUEST[$arg];
                }
            }
        }

        $url = $this->linkRepository->registerClick(@$args['n'], @$args['l'], @$args['p']);

        // Finally redirect to the destination URL
        if ($url) {
            // This gives a proper 303 redirect.
            $this->redirectToUri($url);
        } else {
            throw new \TYPO3\CMS\Core\Error\Http\PageNotFoundException('The requested link was not found', 1440490767);
        }
    }

    /**
     * Returns a configuration for the JsonView, that describes which fields should be rendered for
     * a Link record.
     *
     * @return array
     */
    public static function resolveJsonViewConfiguration()
    {
        return [
            '_exposeObjectIdentifier' => true,
            '_only' => ['url', 'openedCount', 'openedPercentage'],
        ];
    }
}
