<?php

/**
 * An stream of events.
 *
 * @package   Palya\CQRS\Event
 * @copyright Copyright (c) 2013-2015 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Event;

use Palya\CQRS\Bus\MessageInterface;
use Palya\CQRS\Bus\MessageStream;
use Ramsey\Uuid\UuidInterface;

class EventStream extends MessageStream
{
    /**
     * @var UuidInterface
     */
    protected $eventProviderId;

    /**
     * The constructor. Initializes the id of event provider.
     * @param UuidInterface $eventProviderId The id of the event provider.
     */
    public function __construct(UuidInterface $eventProviderId)
    {
        $this->eventProviderId = $eventProviderId;
    }

    /**
     * Returns the id of the event provider.
     * @return UuidInterface
     */
    public function getEventProviderId()
    {
        return $this->eventProviderId;
    }

    /**
     * {@inheritdoc}
     */
    public function push(MessageInterface $message)
    {
        /** @var Event $message */
        $this->insert($message, -1 * $message->getVersion());
    }
}
