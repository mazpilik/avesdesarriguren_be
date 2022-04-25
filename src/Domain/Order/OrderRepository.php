<?php

declare(strict_types=1);

namespace App\Domain\Order;

interface OrderRepository
{
    /**
     * @return Order[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function findOrderOfId(int $id): Order;

    /**
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @param string $orderDirection
     * @return Order
     * @throws OrderNotFoundException
     */
    public function findOrdersSorted(int $limit, int $offset, string $orderBy, string $orderDirection): array;

    /**
     * @param int $id
     * @return void
     */
    public function deleteOrder(int $id): void;

    /**
     * Create a new order
     * @param string $name
     * 
     * @return Order
     */ 
    public function createOrder(string $name): Order;

    /**
     * Update an existing order
     * @param int $id
     * @return Order
     */
    public function updateOrder(int $id): Order;
}
