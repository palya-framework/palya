<?php

/**
 * An identity map to be used for event streams in the event store to avoid
 * creating new streams on each call. This map is based on a simple in-memory
 * array.
 *
 * @package   Palya\CQRS\EventStore\IdentityMap
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\InMemory\EventStore\IdentityMap;

use Palya\CQRS\Event\EventStream;
use Palya\CQRS\EventStore\IdentityMap\IdentityMapInterface;
use Ramsey\Uuid\UuidInterface;

class InMemoryIdentityMap implements IdentityMapInterface
{
    /**
     * The event streams.
     * @var array
     */
    protected $eventStreams;

    /**
     * The constructor. Initializes the event streams.
     */
    public function __construct()
    {
        $this->eventStreams = [];
    }

    /**
     * {@inheritdoc}
     */
    public function get(UuidInterface $id)
    {
        return $this->has($id) ? $this->eventStreams[$id->toString()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function has(UuidInterface $id)
    {
        return isset($this->eventStreams[$id->toString()]);
    }

    /**
     * {@inheritdoc}
     */
    public function set(UuidInterface $id, EventStream $eventStream)
    {
        $this->eventStreams[$id->toString()] = $eventStream;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->eventStreams = [];
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->eventStreams);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->eventStreams);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->eventStreams);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->eventStreams);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return false !== current($this->eventStreams);
    }
}
