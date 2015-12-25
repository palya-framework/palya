<?php

/**
 * The abstract storage for an event store based on the native MongoDB client.
 *
 * @package   Palya\CQRS\Adapter\Mongo\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\Mongo\EventStore\Storage;

use JMS\Serializer\SerializerInterface;

abstract class AbstractMongoStorage
{
    /**
     * The client.
     * @var \MongoClient
     */
    protected $client;

    /**
     * The options.
     * @var MongoStorageOptions
     */
    protected $options;

    /**
     * The serializer.
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * The constructor. Initializes the client, the options and the serializer.
     * @param \MongoClient $client The client.
     * @param MongoStorageOptions $options The options.
     * @param SerializerInterface $serializer The serializer.
     */
    public function __construct(
        \MongoClient $client,
        MongoStorageOptions $options,
        SerializerInterface $serializer
    ) {
        $this->client = $client;
        $this->options = $options;
        $this->serializer = $serializer;
    }
}
