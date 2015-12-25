<?php

namespace PalyaTest\CQRS;

use Palya\CQRS\EventStore\EventStoreInterface;
use PalyaTest\CQRS\TestAsset\DomainObject\Customer;
use PalyaTest\CQRS\TestAsset\Repository\CustomerRepository;
use PalyaTest\CQRS\TestAsset\Repository\CustomerSnapshotRepository;
use PalyaTest\CQRS\TestAsset\ValueObject\CartEntry;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerAddress;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerName;
use PalyaTest\CQRS\TestAsset\ValueObject\Product;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractEventStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CustomerSnapshotRepository
     */
    protected $customerSnapshotRepository;

    public function testCreateCustomer()
    {
        $customer = $this->createCustomer();
        $persisted = $this->saveCustomer($customer);

        $name = $persisted->getName();
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(CustomerName::GENDER_MALE, $name->getGender());
        $this->assertEquals('abc', $name->getFirstName());
        $this->assertEquals('def', $name->getLastName());
        $this->assertEquals('abc.def@ghi.de', $name->getEmail());

        $this->assertCount(1, $eventStream);
    }

    public function testChangeCustomerName()
    {
        $name = new CustomerName(CustomerName::GENDER_FEMALE, 'cba', 'fed', 'cba.fed@ihg.de');

        $customer = $this->createCustomer();
        $customer->changeName($name);
        $persisted = $this->saveCustomer($customer);

        $name = $persisted->getName();
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(CustomerName::GENDER_FEMALE, $name->getGender());
        $this->assertEquals('cba', $name->getFirstName());
        $this->assertEquals('fed', $name->getLastName());
        $this->assertEquals('cba.fed@ihg.de', $name->getEmail());

        $this->assertCount(2, $eventStream);
    }

    /**
     * @group cqrs1000
     */
    public function testChangeCustomerNameMultipleTimes()
    {
        $customer = $this->createCustomer();

        for ($i = 1; $i < 1000; $i++) {
            $name = new CustomerName(CustomerName::GENDER_FEMALE, (string) $i, (string) $i, sprintf('%s.%s@%s.de', $i, $i, $i));
            $customer->changeName($name);
        }

        $persisted = $this->saveCustomer($customer);

        $name = $persisted->getName();
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(CustomerName::GENDER_FEMALE, $name->getGender());
        $this->assertEquals('999', $name->getFirstName());
        $this->assertEquals('999', $name->getLastName());
        $this->assertEquals('999.999@999.de', $name->getEmail());

        $this->assertCount(1000, $eventStream);
    }

    public function testAddCustomerAddress()
    {
        $address = new CustomerAddress(
            Uuid::uuid4(),
            CustomerAddress::TYPE_BILLING,
            'lkj',
            '1',
            '54321',
            'onm',
            'rqp',
            'uts',
            '13579'
        );

        $customer = $this->createCustomer();
        $customer->addAddress($address);
        $persisted = $this->saveCustomer($customer);

        $address = $persisted->getAddress($address->getId());
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(CustomerAddress::TYPE_BILLING, $address->getType());
        $this->assertEquals('lkj', $address->getStreet());
        $this->assertEquals('1', $address->getStreetNumber());
        $this->assertEquals('54321', $address->getPostalCode());
        $this->assertEquals('onm', $address->getCity());
        $this->assertEquals('rqp', $address->getState());
        $this->assertEquals('uts', $address->getCountry());
        $this->assertEquals('13579', $address->getPhone());

        $this->assertCount(2, $eventStream);
    }

    public function testChangeCustomerAddress()
    {
        $id = Uuid::uuid4();

        $addressOne = new CustomerAddress(
            $id,
            CustomerAddress::TYPE_BILLING,
            'lkj',
            '1',
            '54321',
            'onm',
            'rqp',
            'uts',
            '13579'
        );

        $addressTwo = new CustomerAddress(
            $id,
            CustomerAddress::TYPE_BILLING,
            'jkl',
            '2',
            '12345',
            'mno',
            'pqr',
            'stu',
            '97531'
        );

        $customer = $this->createCustomer();
        $customer->addAddress($addressOne);
        $customer->changeAddress($addressTwo);
        $persisted = $this->saveCustomer($customer);

        $address = $persisted->getAddress($id);
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(CustomerAddress::TYPE_BILLING, $address->getType());
        $this->assertEquals('jkl', $address->getStreet());
        $this->assertEquals('2', $address->getStreetNumber());
        $this->assertEquals('12345', $address->getPostalCode());
        $this->assertEquals('mno', $address->getCity());
        $this->assertEquals('pqr', $address->getState());
        $this->assertEquals('stu', $address->getCountry());
        $this->assertEquals('97531', $address->getPhone());

        $this->assertCount(3, $eventStream);
    }

    public function testRemoveCustomerAddress()
    {
        $address = new CustomerAddress(
            Uuid::uuid4(),
            CustomerAddress::TYPE_BILLING,
            'lkj',
            '1',
            '54321',
            'onm',
            'rqp',
            'uts',
            '13579'
        );

        $customer = $this->createCustomer();
        $customer->addAddress($address);
        $customer->removeAddress($address->getId());
        $persisted = $this->saveCustomer($customer);

        $address = $persisted->getAddress($address->getId());
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertNull($address);

        $this->assertCount(3, $eventStream);
    }

    public function testAddCart()
    {
        $cartId = Uuid::uuid4();

        $customer = $this->createCustomer();
        $customer->addCart($cartId);
        $persisted = $this->saveCustomer($customer);

        $cart = $persisted->getCart($cartId);
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertNotEmpty($cart->getId()->toString());

        $this->assertCount(3, $eventStream);
    }

    public function testRemoveCart()
    {
        $cartId = Uuid::uuid4();

        $customer = $this->createCustomer();
        $customer->addCart($cartId);
        $customer->removeCart($cartId);
        $persisted = $this->saveCustomer($customer);

        $cart = $persisted->getCart($cartId);
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertNull($cart);

        $this->assertCount(4, $eventStream);
    }

    public function testAddCartEntry()
    {
        $cartId = Uuid::uuid4();

        $customer = $this->createCustomer();
        $customer->addCart($cartId);
        $cart = $customer->getCart($cartId);

        $entry = new CartEntry(
            Uuid::uuid4(),
            1,
            new Product('abc', 10.00, 'EUR')
        );

        $cart->addEntry($entry);
        $persisted = $this->saveCustomer($customer);

        $cart = $persisted->getCart($cart->getId());
        $entry = $cart->getEntry($entry->getId());
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(1, $entry->getAmount());
        $this->assertEquals('abc', $entry->getProduct()->getName());
        $this->assertEquals(10.00, $entry->getProduct()->getPrice());
        $this->assertEquals('EUR', $entry->getProduct()->getCurrency());

        $this->assertCount(4, $eventStream);
    }

    public function testChangeCartEntry()
    {
        $cartId = Uuid::uuid4();
        $cartEntryId = Uuid::uuid4();

        $customer = $this->createCustomer();
        $customer->addCart($cartId);
        $cart = $customer->getCart($cartId);

        $entryOne = new CartEntry(
            $cartEntryId,
            1,
            new Product('abc', 10.00, 'EUR')
        );

        $entryTwo = new CartEntry(
            $cartEntryId,
            3,
            new Product('ghi', 30.00, 'EUR')
        );

        $cart->addEntry($entryOne);
        $cart->changeEntry($entryTwo);
        $persisted = $this->saveCustomer($customer);

        $cart = $persisted->getCart($cart->getId());
        $entry = $cart->getEntry($cartEntryId);
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertEquals(3, $entry->getAmount());
        $this->assertEquals('ghi', $entry->getProduct()->getName());
        $this->assertEquals(30.00, $entry->getProduct()->getPrice());
        $this->assertEquals('EUR', $entry->getProduct()->getCurrency());

        $this->assertCount(5, $eventStream);
    }

    public function testRemoveCartEntry()
    {
        $cartId = Uuid::uuid4();

        $customer = $this->createCustomer();
        $customer->addCart($cartId);
        $cart = $customer->getCart($cartId);

        $entry = new CartEntry(
            Uuid::uuid4(),
            1,
            new Product('abc', 10.00, 'EUR')
        );

        $cart->addEntry($entry);
        $cart->removeEntry($entry->getId());
        $persisted = $this->saveCustomer($customer);

        $cart = $persisted->getCart($cart->getId());
        $entry = $cart->getEntry($entry->getId());
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertNull($entry);

        $this->assertCount(5, $eventStream);
    }

    public function testCreateMultipleCustomers()
    {
        $nameOne = new CustomerName(CustomerName::GENDER_FEMALE, 'cba', 'fed', 'cba.fed@ihg.de');

        $customerOne = $this->createCustomer();
        $customerOne->changeName($nameOne);
        $this->saveCustomer($customerOne);

        $id = Uuid::uuid4();

        $addressTwoOne = new CustomerAddress(
            $id,
            CustomerAddress::TYPE_BILLING,
            'lkj',
            '1',
            '54321',
            'onm',
            'rqp',
            'uts',
            '13579'
        );

        $addressTwoTwo = new CustomerAddress(
            $id,
            CustomerAddress::TYPE_BILLING,
            'jkl',
            '2',
            '12345',
            'mno',
            'pqr',
            'stu',
            '97531'
        );

        $customerTwo = $this->createCustomer();
        $customerTwo->addAddress($addressTwoOne);
        $customerTwo->changeAddress($addressTwoTwo);
        $this->saveCustomer($customerTwo);

        $eventStreamOne = $this->eventStore->getEventStream($customerOne->getId());
        $eventStreamTwo = $this->eventStore->getEventStream($customerTwo->getId());

        $this->assertCount(2, $eventStreamOne);
        $this->assertCount(3, $eventStreamTwo);
    }

    public function testMultipleEventSources()
    {
        $cartId = Uuid::uuid4();

        $customer = $this->createCustomer();
        $customer->addCart($cartId);
        $cart = $customer->getCart($cartId);

        $entry = new CartEntry(
            Uuid::uuid4(),
            1,
            new Product('abc', 10.00, 'EUR')
        );

        $cart->addEntry($entry);
        $cart->removeEntry($entry->getId());

        $address = new CustomerAddress(
            Uuid::uuid4(),
            CustomerAddress::TYPE_BILLING,
            'lkj',
            '1',
            '54321',
            'onm',
            'rqp',
            'uts',
            '13579'
        );


        $customer->addAddress($address);
        $customer->removeAddress($address->getId());
        $persisted = $this->saveCustomer($customer);

        $address = $persisted->getAddress($address->getId());
        $cart = $persisted->getCart($cart->getId());
        $entry = $cart->getEntry($entry->getId());
        $eventStream = $this->eventStore->getEventStream($customer->getId());

        $this->assertNull($address);
        $this->assertNull($entry);

        $this->assertCount(7, $eventStream);
    }

    public function testSnapshotIsShorteningEventStream()
    {
        $customer = $this->createCustomer();

        for ($i = 1; $i < 10; $i++) {
            $name = new CustomerName(CustomerName::GENDER_FEMALE, (string) $i, (string) $i, sprintf('%s.%s@%s.de', $i, $i, $i));
            $customer->changeName($name);
        }

        $this->saveCustomerSnapshot($customer);
        $persisted = $this->getCustomerSnapshot($customer->getId());

        $name = $persisted->getName();

        $this->assertEquals(CustomerName::GENDER_FEMALE, $name->getGender());
        $this->assertEquals('9', $name->getFirstName());
        $this->assertEquals('9', $name->getLastName());
        $this->assertEquals('9.9@9.de', $name->getEmail());
    }

    public function testMultipleSnapshotsAreShorteningEventStream()
    {
        $customer = $this->createCustomer();

        for ($i = 1; $i < 10; $i++) {
            $name = new CustomerName(CustomerName::GENDER_FEMALE, (string) $i, (string) $i, sprintf('%s.%s@%s.de', $i, $i, $i));
            $customer->changeName($name);
        }

        $this->saveCustomerSnapshot($customer);

        for ($i = 100; $i < 110; $i++) {
            $name = new CustomerName(CustomerName::GENDER_FEMALE, (string) $i, (string) $i, sprintf('%s.%s@%s.de', $i, $i, $i));
            $customer->changeName($name);
        }

        $this->saveCustomerSnapshot($customer);
        $persisted = $this->getCustomerSnapshot($customer->getId());

        $name = $persisted->getName();

        $this->assertEquals(CustomerName::GENDER_FEMALE, $name->getGender());
        $this->assertEquals('109', $name->getFirstName());
        $this->assertEquals('109', $name->getLastName());
        $this->assertEquals('109.109@109.de', $name->getEmail());
    }

    public function testEventsAreAppliedAfterSnapshot()
    {
        $customer = $this->createCustomer();

        for ($i = 1; $i < 10; $i++) {
            $name = new CustomerName(CustomerName::GENDER_FEMALE, (string) $i, (string) $i, sprintf('%s.%s@%s.de', $i, $i, $i));
            $customer->changeName($name);
        }

        $this->saveCustomerSnapshot($customer);

        for ($i = 100; $i < 120; $i++) {
            $name = new CustomerName(CustomerName::GENDER_FEMALE, (string) $i, (string) $i, sprintf('%s.%s@%s.de', $i, $i, $i));
            $customer->changeName($name);
        }

        $this->saveCustomer($customer);
        $persisted = $this->getCustomerSnapshot($customer->getId());

        $name = $persisted->getName();

        $this->assertEquals(CustomerName::GENDER_FEMALE, $name->getGender());
        $this->assertEquals('119', $name->getFirstName());
        $this->assertEquals('119', $name->getLastName());
        $this->assertEquals('119.119@119.de', $name->getEmail());
    }

    protected function createCustomer()
    {
        $id = Uuid::uuid4();
        $name = new CustomerName(CustomerName::GENDER_MALE, 'abc', 'def', 'abc.def@ghi.de');

        return Customer::create($id, $name);
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    protected function saveCustomer(Customer $customer)
    {
        $this->customerRepository->save($customer);
        $this->eventStore->commit();

        return $this->customerRepository->findById($customer->getId());
    }

    protected function saveCustomerSnapshot(Customer $customer)
    {
        $this->customerSnapshotRepository->save($customer);
    }

    /**
     * @param UuidInterface $id
     * @return Customer
     */
    protected function getCustomerSnapshot(UuidInterface $id)
    {
        return $this->customerSnapshotRepository->findById($id);
    }
}
