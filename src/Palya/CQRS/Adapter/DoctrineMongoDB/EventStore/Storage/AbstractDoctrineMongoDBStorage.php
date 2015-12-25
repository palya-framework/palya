<?php

/**
 * The abstract storage for an event store based on the Doctrine MongoDB
 * implementation.
 *
 * @package   Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage;

use Doctrine\MongoDB\Connection;
use JMS\Serializer\SerializerInterface;

abstract class AbstractDoctrineMongoDBStorage
{
    /**
     * The connection.
     * @var Connection
     */
    protected $connection;

    /**
     * The options.
     * @var DoctrineMongoDBStorageOptions
     */
    protected $options;

    /**
     * The serializer.
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * The constructor. Initializes the connection, the options and the serializer.
     * @param Connection $connection The connection.
     * @param DoctrineMongoDBStorageOptions $options The options.
     * @param SerializerInterface $serializer The serializer.
     */
    public function __construct(
        Connection $connection,
        DoctrineMongoDBStorageOptions $options,
        SerializerInterface $serializer
    ) {
        $this->connection = $connection;
        $this->options = $options;
        $this->serializer = $serializer;
    }
}
