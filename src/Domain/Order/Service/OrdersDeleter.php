<?php

namespace App\Domain\Order\Service;

use App\Domain\Order\Repository\OrderDeleteRepository;

final class OrdersDeleter
{
    private OrderDeleteRepository $orderDeleteRepository;

    public function __construct(OrderDeleteRepository $orderDeleteRepository)
    {
        $this->orderDeleteRepository = $orderDeleteRepository;
    }

    public function delete(int $id): string
    {
        return $this->orderDeleteRepository->delete($id);
    }
}