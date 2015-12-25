<?php

namespace PalyaTest\CQRS\TestAsset\Repository;

use Palya\CQRS\Repository\AbstractSnapshotRepository;
use PalyaTest\CQRS\TestAsset\DomainObject\Customer;

class CustomerSnapshotRepository extends AbstractSnapshotRepository
{
    protected function createEventProvider()
    {
        return Customer::createEmpty();
    }
}
