<?php

/**
 * A bus using AMQP as a transfer protocol.
 *
 * @package   Palya\CQRS\Adapter\AMQP\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\AMQP\Bus;

use Palya\CQRS\Bus\AbstractBus;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPBus extends AbstractBus
{
    /**
     * The connection.
     * @var AMQPConnection
     */
    protected $connection;

    /**
     * The options.
     * @var AMQPOptions
     */
    protected $options;

    /**
     * The constructor. Initializes the connection and the options.
     * @param AMQPConnection $connection The connection.
     * @param AMQPOptions $options The options.
     */
    public function __construct(AMQPConnection $connection, AMQPOptions $options)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($this->options->getQueue(), false, false, false, false);

        while (!$this->messages->isEmpty()) {
            $channel->batch_basic_publish(
                new AMQPMessage(serialize($this->messages->dequeue())), '', $this->options->getQueue()
            );
        }

        $channel->publish_batch();
        $channel->close();
    }
}
