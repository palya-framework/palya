<?php

namespace PalyaTest\CQRS\TestAsset\Command;

use Palya\CQRS\Bus\AbstractMessage;
use Ramsey\Uuid\UuidInterface;

class ChangeCustomerNameCommand extends AbstractMessage
{
    /**
     * @var UuidInterface
     */
    protected $customerId;

    /**
     * @var int
     */
    protected $gender;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param UuidInterface $customerId
     * @param int $gender
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function __construct(
        UuidInterface $customerId,
        $gender,
        $firstName,
        $lastName,
        $email
    ) {
        $this->customerId = $customerId;
        $this->gender = $gender;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * @return UuidInterface
     */
    public function getCustomerId()
    {
        return $this->customerId;
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
