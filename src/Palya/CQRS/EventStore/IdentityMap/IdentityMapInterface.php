<?php

/**
 * The interface for an identity map.
 *
 * @package   Palya\CQRS\EventStore\IdentityMap
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore\IdentityMap;

use Palya\CQRS\Event\EventStream;
use Ramsey\Uuid\UuidInterface;

interface IdentityMapInterface extends \Iterator
{
    /**
     * Returns the event stream associated with the id from the identity map.
     * @param UuidInterface $id The id.
     * @return null|EventStream The event stream or null if none is found.
     */
    public function get(UuidInterface $id);

    /**
     * Returns whether an event stream associated with the id is contained in the identity map.
     * @param UuidInterface $id The id.
     * @return bool
     */
    public function has(UuidInterface $id);

    /**
     * Sets the event stream with it's associated id in the identity map.
     * @param UuidInterface $id The id.
     * @param EventStream $eventStream The event stream.
     */
    public function set(UuidInterface $id, EventStream $eventStream);

    /**
     * Removes all event streams from the identity map.
     */
    public function clear();
}
