<?php

namespace PalyaTest\CQRS\TestAsset\CommandHandler;

use Palya\CQRS\Bus\MessageInterface;
use Palya\CQRS\CommandHandler\AbstractCommandHandler;
use PalyaTest\CQRS\TestAsset\Command\CreateCustomerCommand;
use PalyaTest\CQRS\TestAsset\DomainObject\Customer;
use PalyaTest\CQRS\TestAsset\ValueObject\CustomerName;

class CreateCustomerCommandHandler extends AbstractCommandHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(MessageInterface $message)
    {
        /** @var CreateCustomerCommand $message */
        $name = new CustomerName(
            $message->getGender(),
            $message->getFirstName(),
            $message->getLastName(),
            $message->getEmail()
        );

        $this->repository->save(Customer::create($message->getCustomerId(), $name));
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageSubscriptions()
    {
        return [CreateCustomerCommand::CLASS];
    }
}
