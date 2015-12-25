<?php

/**
 * An interface indicating that a domain supplies a config for the service
 * locator.
 *
 * @package   Palya\Domain\DomainManager\Provider
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\DomainManager\Provider;

interface ServiceLocatorConfigProviderInterface
{
    /**
     * Returns the config for the service locator.
     * @return array The config for the service locator.
     */
    public function getServiceLocatorConfig();
}
