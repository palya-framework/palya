<?php

/**
 * The storage for an snapshot store based on the native MongoDB client.
 *
 * @package   Palya\CQRS\Adapter\Mongo\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\Mongo\EventStore\Storage;

use Palya\CQRS\EventStore\EventProviderInterface;
use Palya\CQRS\EventStore\EventStoreException;
use Palya\CQRS\EventStore\Storage\SnapshotStorageInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class MongoSnapshotStorage extends AbstractMongoStorage implements SnapshotStorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSnapshot(UuidInterface $id)
    {
        try {
            $cursor = $this->client
                ->selectCollection($this->options->getDatabase(), $id->toString())
                ->find()
                ->sort(['$natural' => -1])
                ->limit(1);
        } catch (\Exception $e) {
            throw new EventStoreException(__METHOD__ . ' : ' . $e->getMessage());
        }

        $snapshot = $cursor->current();

        if (!isset($snapshot)) {
            return null;
        }

        $eventProvider = $this->serializer->deserialize(
            $snapshot['memento']['data'], $snapshot['memento']['type'], 'json'
        );

        return $eventProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function saveSnapshot(EventProviderInterface $eventProvider)
    {
        $eventProviderId = $eventProvider->getId()->toString();

        $data = [
            'data' => $this->serializer->serialize($eventProvider, 'array'),
            'type' => get_class($eventProvider)
        ];

        $document = [
            'id' => Uuid::uuid4()->toString(),
            'eventProviderId' => $eventProviderId,
            'data' => $data,
            'version' => $eventProvider->getVersion(),
            'time' => new \MongoDate()
        ];

        try {
            $this->client
                ->selectCollection($this->options->getDatabase(), $eventProviderId)
                ->insert($document);
        } catch (\MongoCursorException $e) {
            throw new EventStoreException(__METHOD__ . ' : ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new EventStoreException(__METHOD__ . ' : ' . $e->getMessage());
        }
    }
}
