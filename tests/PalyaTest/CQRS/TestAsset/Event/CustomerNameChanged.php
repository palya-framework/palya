<?php

namespace PalyaTest\CQRS\TestAsset\Event;

use JMS\Serializer\Annotation as JMS;
use Palya\CQRS\Event\Event;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerName;
use Ramsey\Uuid\UuidInterface;

class CustomerNameChanged extends Event
{
    /**
     * @var CustomerName
     * @JMS\Type("PalyaTest\CQRS\TestAsset\ValueObject\CustomerName")
     */
    protected $customerName;

    /**
     * @param UuidInterface $eventProviderId
     * @param CustomerName $customerName
     */
    public function __construct(UuidInterface $eventProviderId, CustomerName $customerName)
    {
        parent::__construct($eventProviderId);
        $this->customerName = $customerName;
    }

    /**
     * @return CustomerName
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }
}
