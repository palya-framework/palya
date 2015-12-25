<?php

namespace PalyaTest\CQRS;

use Palya\CQRS\Adapter\InMemory\EventStore\IdentityMap\InMemoryIdentityMap;
use Palya\CQRS\Adapter\InMemory\EventStore\Storage\InMemoryEventStorage;
use Palya\CQRS\Adapter\Symfony\Bus\SymfonyEventDispatcherBus;
use Palya\CQRS\Bus\BusInterface;
use Palya\CQRS\Bus\MessageStream;
use Palya\CQRS\Bus\NullBus;
use Palya\CQRS\EventStore\EventStore;
use Palya\CQRS\EventStore\EventStoreInterface;
use Palya\CQRS\Repository\EventProviderRepositoryInterface;
use PalyaTest\CQRS\TestAsset\Command\ChangeCustomerNameCommand;
use PalyaTest\CQRS\TestAsset\Command\CreateCustomerCommand;
use PalyaTest\CQRS\TestAsset\CommandHandler\ChangeCustomerNameCommandHandler;
use PalyaTest\CQRS\TestAsset\CommandHandler\CreateCustomerCommandHandler;
use PalyaTest\CQRS\TestAsset\DomainObject\Customer;
use PalyaTest\CQRS\TestAsset\Repository\CustomerRepository;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerName;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @group cqrs
 */
class CQRSTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * @var EventProviderRepositoryInterface
     */
    protected $repository;

    /**
     * @var BusInterface
     */
    protected $commandBus;

    public function testCreateCustomer()
    {
        $id = Uuid::uuid4();

        $stream = new MessageStream();
        $stream->push(new CreateCustomerCommand($id, CustomerName::GENDER_MALE, 'abc', 'def', 'ghi@jkl.de'));

        $this->commandBus->publish($stream);
        $this->commandBus->commit();
        $this->eventStore->commit();

        /** @var Customer $persisted */
        $persisted = $this->repository->findById($id);
        $name = $persisted->getName();

        $this->assertEquals(CustomerName::GENDER_MALE, $name->getGender());
        $this->assertEquals('abc', $name->getFirstName());
        $this->assertEquals('def', $name->getLastName());
        $this->assertEquals('ghi@jkl.de', $name->getEmail());
    }

    public function testChangeCustomerName()
    {
        $id = Uuid::uuid4();

        $stream = new MessageStream();
        $stream->push(new CreateCustomerCommand($id, CustomerName::GENDER_MALE, 'abc', 'def', 'ghi@jkl.de'));
        $stream->push(new ChangeCustomerNameCommand($id, CustomerName::GENDER_MALE, 'mno', 'pqr', 'stu@vwx.de'));

        $this->commandBus->publish($stream);
        $this->commandBus->commit();
        $this->eventStore->commit();

        /** @var Customer $persisted */
        $persisted = $this->repository->findById($id);
        $name = $persisted->getName();

        $this->assertEquals(CustomerName::GENDER_MALE, $name->getGender());
        $this->assertEquals('mno', $name->getFirstName());
        $this->assertEquals('pqr', $name->getLastName());
        $this->assertEquals('stu@vwx.de', $name->getEmail());
    }

    protected function setUp()
    {
        $this->eventStore = new EventStore(
            new InMemoryEventStorage(),
            new NullBus(),
            new InMemoryIdentityMap()
        );

        $this->repository = new CustomerRepository($this->eventStore);

        $this->commandBus = new SymfonyEventDispatcherBus(new EventDispatcher());
        $this->commandBus->subscribe(new CreateCustomerCommandHandler($this->repository));
        $this->commandBus->subscribe(new ChangeCustomerNameCommandHandler($this->repository));
    }
}
