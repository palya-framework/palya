<?php

namespace PalyaTest\CQRS\TestAsset\ValueObject;

use Assert\Assertion;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

class CartEntry
{
    /**
     * @var UuidInterface
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    protected $id;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $amount;

    /**
     * @var Product
     * @JMS\Type("PalyaTest\CQRS\TestAsset\ValueObject\Product")
     */
    protected $product;

    /**
     * @param UuidInterface $id
     * @param int $amount
     * @param Product $product
     */
    public function __construct(UuidInterface $id, $amount, Product $product)
    {
        Assertion::uuid($id->toString());
        Assertion::integer($amount);
        Assertion::notEmpty($product);

        $this->id = $id;
        $this->amount = $amount;
        $this->product = $product;
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
