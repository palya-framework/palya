<?php

/**
 * An abstract bus.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

abstract class AbstractBus implements BusInterface
{
    /**
     * The messages.
     * @var \SplQueue
     */
    protected $messages;

    /**
     * The constructor. Initializes the messages.
     */
    public function __construct()
    {
        $this->messages = new \SplQueue();
    }

    /**
     * Publishes the message stream to the bus.
     * @param MessageStream $messageStream The message stream.
     */
    public function publish(MessageStream $messageStream)
    {
        foreach ($messageStream as $message) {
            $this->messages->enqueue($message);
        }
    }

    /**
     * Cancels all the changes of a transaction.
     */
    public function rollback()
    {
        while (!$this->messages->isEmpty()) {
            $this->messages->dequeue();
        }
    }
}
