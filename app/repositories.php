<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Order\InDbOrderRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        OrderRepository::class => \DI\autowire(InDbOrderRepository::class),
    ]);
};
