<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Order;

use App\Domain\User\Order;
use App\Domain\User\OrderNotFoundException;
use App\Domain\User\OrderRepository;

class InDbOrderRepository implements OrderRepository
{
    /**
     * @var Order[]
     */
    private array $orders;

    /**
     * @param User[]|null $users
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }
}
