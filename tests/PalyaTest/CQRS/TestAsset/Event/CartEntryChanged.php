<?php

namespace PalyaTest\CQRS\TestAsset\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Event\Event;
use PalyaTest\CQRS\TestAsset\ValueObject\CartEntry;
use Ramsey\Uuid\UuidInterface;

class CartEntryChanged extends Event
{
    /**
     * @var CartEntry
     * @JMS\Type("PalyaTest\CQRS\TestAsset\ValueObject\CartEntry")
     */
    protected $entry;

    /**
     * @param UuidInterface $eventProviderId
     * @param CartEntry $entry
     */
    public function __construct(UuidInterface $eventProviderId, CartEntry $entry)
    {
        parent::__construct($eventProviderId);
        $this->entry = $entry;
    }

    /**
     * @return CartEntry
     */
    public function getEntry()
    {
        return $this->entry;
    }
}
