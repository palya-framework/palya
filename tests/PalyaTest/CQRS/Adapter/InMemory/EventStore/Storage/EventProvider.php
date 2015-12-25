<?php

namespace PalyaTest\CQRS\Adapter\InMemory\EventStore\Storage;

use Palya\CQRS\DomainObject\AbstractAggregate;
use Ramsey\Uuid\UuidInterface;

class EventProvider extends AbstractAggregate
{
    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }
}
