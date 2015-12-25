<?php

namespace PalyaTest\CQRS\TestAsset\CommandHandler;

use Palya\CQRS\Bus\MessageInterface;
use Palya\CQRS\CommandHandler\AbstractCommandHandler;
use PalyaTest\CQRS\TestAsset\Command\ChangeCustomerNameCommand;
use PalyaTest\CQRS\TestAsset\DomainObject\Customer;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerName;
use Ramsey\Uuid\Uuid;

class ChangeCustomerNameCommandHandler extends AbstractCommandHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(MessageInterface $message)
    {
        /** @var ChangeCustomerNameCommand $message */
        $name = new CustomerName(
            $message->getGender(),
            $message->getFirstName(),
            $message->getLastName(),
            $message->getEmail()
        );

        /** @var Customer $customer */
        $customer = $this->repository->findById(Uuid::fromString($message->getCustomerId()));
        $customer->changeName($name);

        $this->repository->save($customer);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageSubscriptions()
    {
        return [ChangeCustomerNameCommand::CLASS];
    }
}
