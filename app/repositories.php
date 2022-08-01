<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    //$services = $containerBuilder
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        TicketRepository::class => \DI\autowire(TicketRepository::class),
    ]);
};
