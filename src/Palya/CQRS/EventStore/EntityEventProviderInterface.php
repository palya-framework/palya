<?php

/**
 * The interface for an entity event provider.
 *
 * @package   Palya\CQRS\EventStore
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore;

interface EntityEventProviderInterface extends EventProviderInterface
{
    /**
     * Registers the version provider for this entity.
     * @param VersionProvider $versionProvider The version provider.
     */
    public function registerVersionProvider(VersionProvider $versionProvider);
}
