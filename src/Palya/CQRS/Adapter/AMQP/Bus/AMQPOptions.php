<?php

/**
 * The options for the bus based on the AMQP protocol.
 *
 * @package   Palya\CQRS\Adapter\AMQP\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\AMQP\Bus;

class AMQPOptions
{
    /**
     * The queue.
     * @var string
     */
    protected $queue;

    /**
     * The constructor. Initializes the queue.
     * @param string $queue The queue.
     */
    public function __construct($queue)
    {
        $this->queue = $queue;
    }

    /**
     * Returns the queue.
     * @return string The queue.
     */
    public function getQueue()
    {
        return $this->queue;
    }
}
