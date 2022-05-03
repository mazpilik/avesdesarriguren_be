<?php

namespace App\Domain\Order\Service;

use App\Domain\Order\Repository\OrderCreatorRepository;

final class OrdersCreator
{
    private OrderCreatorRepository $orderCreatorRepository;

    public function __construct(OrderCreatorRepository $orderCreatorRepository)
    {
        $this->orderCreatorRepository = $orderCreatorRepository;
    }

    public function create(string $name): string
    {
        return $this->orderCreatorRepository->create($name);
    }
}