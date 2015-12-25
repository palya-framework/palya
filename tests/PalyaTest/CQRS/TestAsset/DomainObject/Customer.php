<?php

namespace PalyaTest\CQRS\TestAsset\DomainObject;

use Palya\CQRS\DomainObject\AbstractAggregate;
use PalyaTest\CQRS\TestAsset\Event\CartAdded;
use PalyaTest\CQRS\TestAsset\Event\CartRemoved;
use PalyaTest\CQRS\TestAsset\Event\CustomerAddressAdded;
use PalyaTest\CQRS\TestAsset\Event\CustomerAddressChanged;
use PalyaTest\CQRS\TestAsset\Event\CustomerAddressRemoved;
use PalyaTest\CQRS\TestAsset\Event\CustomerCreated;
use PalyaTest\CQRS\TestAsset\Event\CustomerNameChanged;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerAddress;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerName;
use Ramsey\Uuid\UuidInterface;

class Customer extends AbstractAggregate
{
    /**
     * @var CustomerName
     */
    protected $name;

    /**
     * @var CustomerAddress[]
     */
    protected $addresses;

    /**
     * @var Cart[]
     */
    protected $carts;

    protected function __construct()
    {
        parent::__construct();
        $this->addresses = [];
        $this->carts = [];
    }

    /**
     * @return Customer
     */
    public static function createEmpty()
    {
        return new self();
    }

    /**
     * @param UuidInterface $id
     * @param CustomerName $name
     * @return Customer
     */
    public static function create(UuidInterface $id, CustomerName $name)
    {
        $customer = new self();
        $customer->applyUncommittedEvent(new CustomerCreated($id, $name));
        return $customer;
    }

    /**
     * @return CustomerName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param UuidInterface $id
     * @return null|CustomerAddress
     */
    public function getAddress(UuidInterface $id)
    {
        return isset($this->addresses[$id->toString()]) ? $this->addresses[$id->toString()] : null;
    }

    /**
     * @return CustomerAddress[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param UuidInterface $id
     * @return null|Cart
     */
    public function getCart(UuidInterface $id)
    {
        return isset($this->carts[$id->toString()]) ? $this->carts[$id->toString()] : null;
    }

    /**
     * @return Cart[]
     */
    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * @param CustomerName $name
     */
    public function changeName(CustomerName $name)
    {
        $this->applyUncommittedEvent(new CustomerNameChanged($this->getId(), $name));
    }

    /**
     * @param CustomerAddress $address
     */
    public function addAddress(CustomerAddress $address)
    {
        $this->applyUncommittedEvent(new CustomerAddressAdded($this->getId(), $address));
    }

    /**
     * @param CustomerAddress $address
     */
    public function changeAddress(CustomerAddress $address)
    {
        $this->applyUncommittedEvent(new CustomerAddressChanged($this->getId(), $address));
    }

    /**
     * @param UuidInterface $id
     */
    public function removeAddress(UuidInterface $id)
    {
        $this->applyUncommittedEvent(new CustomerAddressRemoved($this->getId(), $id));
    }

    /**
     * @param UuidInterface $cartId
     */
    public function addCart(UuidInterface $cartId)
    {
        $this->applyUncommittedEvent(new CartAdded($this->getId(), $cartId));
    }

    /**
     * @param UuidInterface $cartId
     */
    public function removeCart(UuidInterface $cartId)
    {
        $this->applyUncommittedEvent(new CartRemoved($this->getId(), $cartId));
    }

    /**
     * @param CustomerCreated $event
     */
    protected function onCustomerCreated(CustomerCreated $event)
    {
        $this->id = $event->getEventProviderId();
        $this->name = $event->getCustomerName();
    }

    /**
     * @param CustomerNameChanged $event
     */
    protected function onCustomerNameChanged(CustomerNameChanged $event)
    {
        $this->name = $event->getCustomerName();
    }

    /**
     * @param CustomerAddressAdded $event
     */
    protected function onCustomerAddressAdded(CustomerAddressAdded $event)
    {
        $this->addresses[$event->getCustomerAddress()->getId()->toString()] = $event->getCustomerAddress();
    }

    /**
     * @param CustomerAddressChanged $event
     */
    protected function onCustomerAddressChanged(CustomerAddressChanged $event)
    {
        $this->addresses[$event->getCustomerAddress()->getId()->toString()] = $event->getCustomerAddress();
    }

    /**
     * @param CustomerAddressRemoved $event
     */
    protected function onCustomerAddressRemoved(CustomerAddressRemoved $event)
    {
        unset($this->addresses[$event->getCustomerAddressId()->toString()]);
    }

    /**
     * @param CartAdded $event
     */
    protected function onCartAdded(CartAdded $event)
    {
        $cart = Cart::create($this->versionProvider, $event->getCartId());
        $this->carts[$event->getCartId()->toString()] = $cart;
        $this->registerEntityEventProvider($cart);
    }

    /**
     * @param CartRemoved $event
     */
    protected function onCartRemoved(CartRemoved $event)
    {
        unset($this->carts[$event->getCartId()->toString()]);
    }
}
