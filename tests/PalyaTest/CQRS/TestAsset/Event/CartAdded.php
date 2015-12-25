<?php

namespace PalyaTest\CQRS\TestAsset\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Event\Event;
use Ramsey\Uuid\UuidInterface;

class CartAdded extends Event
{
    /**
     * @var UuidInterface
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    protected $cartId;

    /**
     * @param UuidInterface $eventProviderId
     * @param UuidInterface $cartId
     */
    public function __construct(UuidInterface $eventProviderId, UuidInterface $cartId)
    {
        parent::__construct($eventProviderId);
        $this->cartId = $cartId;
    }

    /**
     * @return UuidInterface
     */
    public function getCartId()
    {
        return $this->cartId;
    }
}
