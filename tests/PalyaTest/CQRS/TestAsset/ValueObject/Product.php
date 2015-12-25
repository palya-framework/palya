<?php

namespace PalyaTest\CQRS\TestAsset\ValueObject;

use Assert\Assertion;
use JMS\Serializer\Annotation as JMS;

class Product
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $name;

    /**
     * @var float
     * @JMS\Type("float")
     */
    protected $price;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $currency;

    /**
     * @param string $name
     * @param float $price
     * @param string $currency
     */
    public function __construct($name, $price, $currency)
    {
        Assertion::notEmpty($name);
        Assertion::float($price);
        Assertion::notEmpty($currency);

        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
