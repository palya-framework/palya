<?php

namespace PalyaTest\CQRS\TestAsset\ValueObject;

use Assert\Assertion;
use JMS\Serializer\Annotation as JMS;

class CustomerName
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $gender;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $firstName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $lastName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $email;

    /**
     * @param int $gender
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function __construct($gender, $firstName, $lastName, $email)
    {
        Assertion::choice($gender, [self::GENDER_MALE, self::GENDER_FEMALE]);
        Assertion::notEmpty($firstName);
        Assertion::notEmpty($lastName);
        Assertion::email($email);

        $this->gender = $gender;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
