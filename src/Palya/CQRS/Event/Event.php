<?php

/**
 * An event.
 *
 * @package   Palya\CQRS\Event
 * @copyright Copyright (c) 2013-2015 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Bus\AbstractMessage;
use Ramsey\Uuid\UuidInterface;

class Event extends AbstractMessage
{
    /**
     * The id of the event provider.
     * @var UuidInterface
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    protected $eventProviderId;

    /**
     * The version.
     * @var int
     * @JMS\Type("integer")
     */
    protected $version;

    /**
     * The constructor. Initializes the id of the event provider.
     * @param UuidInterface $eventProviderId
     */
    public function __construct(UuidInterface $eventProviderId)
    {
        $this->eventProviderId = $eventProviderId;
        $this->version = 0;
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
     * Returns the version.
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the version.
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
