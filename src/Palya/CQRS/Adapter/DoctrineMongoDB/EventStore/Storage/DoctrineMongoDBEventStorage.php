<?php

/**
 * The storage for an event store based on the Doctrine Mongo implementation.
 *
 * @package   Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage;

use Palya\CQRS\Event\Event;
use Palya\CQRS\Event\EventStream;
use Palya\CQRS\EventStore\EventStoreException;
use Palya\CQRS\EventStore\Storage\EventStorageInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DoctrineMongoDBEventStorage extends AbstractDoctrineMongoDBStorage implements EventStorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCommittedEventStream(UuidInterface $id, $version = 0)
    {
        if (0 < $version) {
            $query = ['version' => ['$gte' => $version]];
        } else {
            $query = [];
        }

        try {
            $cursor = $this->connection
                ->selectCollection($this->options->getDatabase(), $id->toString())
                ->find($query);
        } catch (\Exception $e) {
            throw new EventStoreException(__METHOD__ . ' : ' . $e->getMessage());
        }

        $eventStream = new EventStream($id);

        foreach ($cursor as $document) {
            /** @var Event $event */
            $event = $this->serializer->deserialize(
                $document['data']['payload'], $document['data']['type'], 'json'
            );

            $eventStream->push($event);
        }

        return $eventStream;
    }

    /**
     * {@inheritdoc}
     */
    public function saveUncommittedEventStream(EventStream $eventStream)
    {
        $events = [];

        foreach ($eventStream as $event) {
            $data = [
                'payload' => $this->serializer->serialize($event, 'json'),
                'type' => get_class($event)
            ];

            $events[] = [
                'id' => Uuid::uuid4()->toString(),
                'eventProviderId' => $event->getEventProviderId()->toString(),
                'data' => $data,
                'version' => $event->getVersion(),
                'time' => new \MongoDate()
            ];
        }

        try {
            $this->connection
                ->selectCollection($this->options->getDatabase(), $eventStream->getEventProviderId()->toString())
//                ->ensureIndex(['version' => 1], ['unique' => true, 'background' => true])
                ->batchInsert($events);
        } catch (\MongoCursorException $e) {
            throw new EventStoreException(__METHOD__ . ' : ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new EventStoreException(__METHOD__ . ' : ' . $e->getMessage());
        }
    }
}
