<?php

/**
 * An abstract entity.
 *
 * @package   Palya\CQRS\DomainObject
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\DomainObject;

use Palya\CQRS\EventStore\EntityEventProviderInterface;
use Palya\CQRS\EventStore\VersionProvider;

abstract class AbstractEntity extends AbstractDomainObject implements EntityEventProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function registerVersionProvider(VersionProvider $versionProvider)
    {
        $this->versionProvider = $versionProvider;
    }
}
