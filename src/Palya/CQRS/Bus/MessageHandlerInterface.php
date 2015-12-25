<?php

/**
 * The interface for a message handler.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

interface MessageHandlerInterface
{
    /**
     * Handles the message.
     * @param MessageInterface $message The message.
     */
    public function handle(MessageInterface $message);

    /**
     * Returns the message subscriptions.
     * @return array The message subscriptions.
     */
    public function getMessageSubscriptions();
}
