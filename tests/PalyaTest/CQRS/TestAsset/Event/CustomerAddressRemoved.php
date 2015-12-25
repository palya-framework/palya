<?php

namespace PalyaTest\CQRS\TestAsset\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Event\Event;
use Ramsey\Uuid\UuidInterface;

class CustomerAddressRemoved extends Event
{
    /**
     * @var UuidInterface
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    protected $customerAddressId;

    /**
     * @param UuidInterface $eventProviderId
     * @param UuidInterface $customerAddressId
     */
    public function __construct(UuidInterface $eventProviderId, UuidInterface $customerAddressId)
    {
        parent::__construct($eventProviderId);
        $this->customerAddressId = $customerAddressId;
    }

    /**
     * @return UuidInterface
     */
    public function getCustomerAddressId()
    {
        return $this->customerAddressId;
    }
}
