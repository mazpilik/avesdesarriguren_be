<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderNotFoundException;
use App\Domain\Order\OrderRepository;

class InDbOrderRepository implements OrderRepository
{
    /**
     * @var Order[]
     */
    private array $orders;

    /**
     * @param Order[]|null $order
     */
    public function __construct(array $order)
    {
        $this->id = $order['id'];
        $this->name = $order['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $db = $this->get(PDO::class);
        $sth = $db->prepare('SELECT * FROM orders');
        $sth->execute();
        $orders = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
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
