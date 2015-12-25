<?php

/**
 * An interface indicating that a domain supplies a config.
 *
 * @package   Palya\Domain\DomainManager\Provider
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\DomainManager\Provider;

interface DomainConfigProviderInterface
{
    /**
     * Returns the domain config.
     * @return array The domain config.
     */
    public function getDomainConfig();
}
