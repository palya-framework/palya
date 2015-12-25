<?php

/**
 * A version provider. An object that can be shared between aggregates and
 * entities and provides functionality to track the current version.
 *
 * @package   Palya\CQRS\EventStore
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore;

class VersionProvider
{
    /**
     * The version.
     * @var int
     */
    protected $version;

    /**
     * The constructor. Initializes the version.
     */
    public function __construct()
    {
        $this->version = 0;
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
     * Returns the next version.
     * @return int
     */
    public function getNextVersion()
    {
        return ++$this->version;
    }
}
