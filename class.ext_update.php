<?php

use Ecodev\Newsletter\Update\Update;

/**
 * Provide a "update" button in Extension Manager when update are required
 */
class ext_update
{
    /**
     * @var Update
     */
    private $update;

    public function __construct()
    {
        $this->update = new Update();
    }

    /**
     * Do update and return result as HTML
     * @return string HTML content
     */
    public function main()
    {
        return $this->update->update();
    }

    /**
     * Return whether update is required
     * @return bool
     */
    public function access()
    {
        $queries = $this->update->getQueries();

        return !empty($queries);
    }
}
