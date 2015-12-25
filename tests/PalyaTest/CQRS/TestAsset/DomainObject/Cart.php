<?php

namespace PalyaTest\CQRS\TestAsset\DomainObject;

use Palya\CQRS\DomainObject\AbstractEntity;
use Palya\CQRS\EventStore\VersionProvider;
use PalyaTest\CQRS\TestAsset\Event\CartCreated;
use PalyaTest\CQRS\TestAsset\Event\CartEntryAdded;
use PalyaTest\CQRS\TestAsset\Event\CartEntryChanged;
use PalyaTest\CQRS\TestAsset\Event\CartEntryRemoved;
use PalyaTest\CQRS\TestAsset\ValueObject\CartEntry;
use Ramsey\Uuid\UuidInterface;

class Cart extends AbstractEntity
{
    /**
     * @var CartEntry[]
     */
    protected $entries;

    protected function __construct()
    {
        parent::__construct();
        $this->entries = [];
    }

    /**
     * @return Cart
     */
    public static function createEmpty()
    {
        return new self();
    }

    /**
     * @param VersionProvider $versionProvider
     * @param UuidInterface $id
     * @return Cart
     */
    public static function create(VersionProvider $versionProvider, UuidInterface $id)
    {
        $cart = new self();
        $cart->registerVersionProvider($versionProvider);
        $cart->applyUncommittedEvent(new CartCreated($id));
        return $cart;
    }

    /**
     * @param UuidInterface $id
     * @return null|CartEntry
     */
    public function getEntry(UuidInterface $id)
    {
        return isset($this->entries[$id->toString()]) ? $this->entries[$id->toString()] : null;
    }

    /**
     * @return CartEntry[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param CartEntry $entry
     */
    public function addEntry(CartEntry $entry)
    {
        $this->applyUncommittedEvent(new CartEntryAdded($this->getId(), $entry));
    }

    /**
     * @param CartEntry $entry
     */
    public function changeEntry(CartEntry $entry)
    {
        $this->applyUncommittedEvent(new CartEntryChanged($this->getId(), $entry));
    }

    /**
     * @param UuidInterface $id
     */
    public function removeEntry(UuidInterface $id)
    {
        $this->applyUncommittedEvent(new CartEntryRemoved($this->getId(), $id));
    }

    /**
     * @param CartCreated $event
     */
    protected function onCartCreated(CartCreated $event)
    {
        $this->id = $event->getEventProviderId();
    }

    /**
     * @param CartEntryAdded $event
     */
    protected function onCartEntryAdded(CartEntryAdded $event)
    {
        $this->entries[$event->getEntry()->getId()->toString()] = $event->getEntry();
    }

    /**
     * @param CartEntryChanged $event
     */
    protected function onCartEntryChanged(CartEntryChanged $event)
    {
        $this->entries[$event->getEntry()->getId()->toString()] = $event->getEntry();
    }

    /**
     * @param CartEntryRemoved $event
     */
    protected function onCartEntryRemoved(CartEntryRemoved $event)
    {
        unset($this->entries[$event->getCartEntryId()->toString()]);
    }
}
