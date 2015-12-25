<?php

namespace PalyaTest\CQRS\TestAsset\ValueObject;

use Assert\Assertion;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

class CustomerAddress
{
    const TYPE_BILLING = 1;
    const TYPE_SHIPPING = 2;

    /**
     * @var UuidInterface
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    protected $id;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $type;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $street;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $streetNumber;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $postalCode;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $city;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $state;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $country;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $phone;

    /**
     * @param UuidInterface $id
     * @param int $type
     * @param string $street
     * @param string $streetNumber
     * @param string $postalCode
     * @param string $city
     * @param string $state
     * @param string $country
     * @param string $phone
     */
    public function __construct(UuidInterface $id, $type, $street, $streetNumber, $postalCode, $city, $state, $country, $phone)
    {
        Assertion::uuid($id->toString());
        Assertion::choice($type, [self::TYPE_BILLING, self::TYPE_SHIPPING]);
        Assertion::notEmpty($street);
        Assertion::notEmpty($streetNumber);
        Assertion::notEmpty($postalCode);
        Assertion::notEmpty($city);
        Assertion::notEmpty($state);
        Assertion::notEmpty($country);
        Assertion::notEmpty($phone);

        $this->id = $id;
        $this->type = $type;
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->phone = $phone;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
