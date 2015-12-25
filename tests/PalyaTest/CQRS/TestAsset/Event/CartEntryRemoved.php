<?php

namespace PalyaTest\CQRS\TestAsset\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Event\Event;
use Ramsey\Uuid\UuidInterface;

class CartEntryRemoved extends Event
{
    /**
     * @var UuidInterface
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    protected $cartEntryId;

    /**
     * CartEntryRemoved constructor.
     * @param UuidInterface $eventProviderId
     * @param UuidInterface $cartEntryId
     */
    public function __construct(UuidInterface $eventProviderId, UuidInterface $cartEntryId)
    {
        parent::__construct($eventProviderId);
        $this->cartEntryId = $cartEntryId;
    }

    /**
     * @return UuidInterface
     */
    public function getCartEntryId()
    {
        return $this->cartEntryId;
    }
}
