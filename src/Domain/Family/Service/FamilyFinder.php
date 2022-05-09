<?php

namespace App\Domain\Family\Service;

use App\Domain\Family\Repository\FamilyFinderRepository;

final class FamilyFinder
{
    private FamilyFinderRepository $familyFinderRepository;

    public function __construct(FamilyFinderRepository $familyFinderRepository)
    {
        $this->familyFinderRepository = $familyFinderRepository;
    }

    public function findAll(): array
    {
        return $this->familyFinderRepository->findAll();
    }

    public function findByid(int $id): array
    {
        return $this->familyFinderRepository->findByid($id);
    }

    public function findSorted(array $data): array
    {
        return $this->familyFinderRepository->findSorted($data);
    }

    public function getFamilyCount(string $where): int
    {
        return $this->familyFinderRepository->getFamilyCount($where);
    }

    public function findByOrderId(int $orderId): array
    {
        return $this->familyFinderRepository->findByOrderId($orderId);
    }
}