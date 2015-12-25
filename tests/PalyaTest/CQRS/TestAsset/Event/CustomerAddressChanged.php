<?php

namespace PalyaTest\CQRS\TestAsset\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Event\Event;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerAddress;
use Ramsey\Uuid\UuidInterface;

class CustomerAddressChanged extends Event
{
    /**
     * @var CustomerAddress
     * @JMS\Type("PalyaTest\CQRS\TestAsset\ValueObject\CustomerAddress")
     */
    protected $customerAddress;

    /**
     * @param UuidInterface $eventProviderId
     * @param CustomerAddress $customerAddress
     */
    public function __construct(UuidInterface $eventProviderId, CustomerAddress $customerAddress)
    {
        parent::__construct($eventProviderId);
        $this->customerAddress = $customerAddress;
    }

    /**
     * @return CustomerAddress
     */
    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }
}
