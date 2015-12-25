<?php

/**
 * An abstract command handler.
 *
 * @package   Palya\CQRS\CommandHandler
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\CommandHandler;

use Palya\CQRS\Bus\MessageHandlerInterface;
use Palya\CQRS\Repository\EventProviderRepositoryInterface;

abstract class AbstractCommandHandler implements MessageHandlerInterface
{
    /**
     * The repository.
     * @var EventProviderRepositoryInterface
     */
    protected $repository;

    /**
     * The constructor. Initializes the repository.
     * @param EventProviderRepositoryInterface $repository The repository.
     */
    public function __construct(EventProviderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
