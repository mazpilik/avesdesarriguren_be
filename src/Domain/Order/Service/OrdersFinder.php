<?php

namespace App\Domain\Order\Service;

use App\Domain\Order\Repository\OrderFinderRepository;

final class OrdersFinder
{
    private OrderFinderRepository $orderFinderRepository;

    public function __construct(OrderFinderRepository $orderFinderRepository)
    {
        $this->orderFinderRepository = $orderFinderRepository;
    }

    public function findAll(): array
    {
        return $this->orderFinderRepository->findAll();
    }

    public function findByid(int $id): array
    {
        return $this->orderFinderRepository->findByid($id);
    }

    public function findSorted(array $data): array
    {
        return $this->orderFinderRepository->findSorted($data);
    }

    public function getOrderCount(string $where): int
    {
        return $this->orderFinderRepository->getOrderCount($where);
    }
}