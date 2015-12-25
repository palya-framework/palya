<?php

/**
 * The interface for a domain event.
 *
 * @package   Palya\Domain\EventPublisher
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher;

interface DomainEventInterface
{
    /**
     * Returns the name of the domain event.
     * @return string The name of the domain event.
     */
    public function getName();
}
