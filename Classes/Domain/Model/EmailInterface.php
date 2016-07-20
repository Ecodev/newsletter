<?php

namespace Ecodev\Newsletter\Domain\Model;

/**
 * Email interface defining minimal contract to be used by third party
 * in hooks implementations.
 */
interface EmailInterface
{
    /**
     * Getter UID
     *
     * @return int UID
     */
    public function getUid();

    /**
     * Get the recipient address (eg: john@example.com)
     *
     * @return string recipientAddress
     */
    public function getRecipientAddress();

    /**
     * Get recipient data.
     *
     * This return an array of all custom data for this recipient. That
     * typically includes all fields selected in a SQL RecipientList.
     *
     * @return string[] recipientData
     */
    public function getRecipientData();
}
