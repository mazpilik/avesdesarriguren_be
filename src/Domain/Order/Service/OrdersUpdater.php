<?php

namespace App\Domain\Order\Service;

use App\Domain\Order\Repository\OrderUpdaterRepository;

final class OrdersUpdater
{
    private OrderUpdaterRepository $orderUpdaterRepository;

    public function __construct(OrderUpdaterRepository $orderUpdaterRepository)
    {
        $this->orderUpdaterRepository = $orderUpdaterRepository;
    }

    public function update(int $id, string $name): string
    {
        return $this->orderUpdaterRepository->update($id, $name);
    }
}