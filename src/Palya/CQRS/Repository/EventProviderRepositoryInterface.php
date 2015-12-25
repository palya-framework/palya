<?php

/**
 * The interface for a repository for an event provider.
 *
 * @package   Palya\CQRS\Repository
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Repository;

use Palya\CQRS\EventStore\EventProviderInterface;
use Ramsey\Uuid\UuidInterface;

interface EventProviderRepositoryInterface
{
    /**
     * Returns the event provider from the repository.
     * @param UuidInterface $id The id.
     * @return EventProviderInterface The event provider interface.
     */
    public function findById(UuidInterface $id);

    /**
     * Saves the event provider to the repository.
     * @param EventProviderInterface $eventProvider The event provider.
     */
    public function save(EventProviderInterface $eventProvider);
}
