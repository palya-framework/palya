<?php

/**
 * An interface indicating that a domain supplies a config for the domain event
 * subscribers.
 *
 * @package   Palya\Domain\DomainManager\Provider
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\DomainManager\Provider;

interface SubscriberConfigProviderInterface
{
    /**
     * Returns config for the domain event subscribers.
     * @return array The config for the domain event subscribers.
     */
    public function getSubscriberConfig();
}
