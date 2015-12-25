<?php

namespace PalyaTest\CQRS\TestAsset\Repository;

use Palya\CQRS\Repository\AbstractEventProviderRepository;
use PalyaTest\CQRS\TestAsset\DomainObject\Customer;

class CustomerRepository extends AbstractEventProviderRepository
{
    protected function createEventProvider()
    {
        return Customer::createEmpty();
    }
}
