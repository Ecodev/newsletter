<?php

namespace Ecodev\Newsletter\Domain\Model;

/**
 * Link
 */
class Link extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * url
     *
     * @var string
     */
    protected $url = '';

    /**
     * newsletter
     * Here the type is intentionnaly wrong because for some reasons it does not
     * work in TYPO3 7.3 and older if we specify the correct type of Newsletter
     * @lazy
     * @var int
     */
    protected $newsletter;

    /**
     * opened count
     *
     * @var int
     */
    protected $openedCount = 0;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Override parent to manually inject objectManager
     */
    public function initializeObject()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    }

    /**
     * Setter for url
     *
     * @param string $url url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Getter for url
     *
     * @return string url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Setter for newsletter
     *
     * @param \Ecodev\Newsletter\Domain\Model\Newsletter $newsletter newsletter
     */
    public function setNewsletter(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Getter for newsletter
     *
     * @return \Ecodev\Newsletter\Domain\Model\Newsletter newsletter
     */
    public function getNewsletter()
    {
        $newsletterRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\NewsletterRepository::class);

        return $newsletterRepository->findByUid($this->newsletter);
    }

    /**
     * Setter for openedCount
     *
     * @param int $openedCount openedCount
     */
    public function setOpenedCount($openedCount)
    {
        $this->openedCount = $openedCount;
    }

    /**
     * Getter for openedCount
     *
     * @return int openedCount
     */
    public function getOpenedCount()
    {
        return $this->openedCount;
    }

    public function getOpenedPercentage()
    {
        $emailRepository = $this->objectManager->get(\Ecodev\Newsletter\Domain\Repository\EmailRepository::class);
        $emailCount = $emailRepository->getCount($this->newsletter);

        if ($emailCount == 0) {
            return 0;
        }

        return round($this->getOpenedCount() * 100 / $emailCount, 2);
    }
}
