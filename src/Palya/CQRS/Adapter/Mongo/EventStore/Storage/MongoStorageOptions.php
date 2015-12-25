<?php

/**
 * The storage options for an event store based on the native MongoDB client.
 *
 * @package   Palya\CQRS\Adapter\Mongo\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\Mongo\EventStore\Storage;

class MongoStorageOptions
{
    /**
     * The database.
     * @var string
     */
    private $database;

    /**
     * The constructor. Initializes the database.
     * @param string $database The database.
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Returns the database.
     * @return string The database.
     */
    public function getDatabase()
    {
        return $this->database;
    }
}
