<?php

namespace App\Domain\Bird\Service;

use App\Domain\Bird\Repository\BirdFinderRepository;

final class BirdFinder
{
    private BirdFinderRepository $birdFinderRepository;

    public function __construct(BirdFinderRepository $birdFinderRepository)
    {
        $this->birdFinderRepository = $birdFinderRepository;
    }

    public function findAll(): array
    {
        return $this->birdFinderRepository->findAll();
    }

    public function findByid(int $id): array
    {
        return $this->birdFinderRepository->findByid($id);
    }

    public function findSorted(array $data): array
    {

        return $this->birdFinderRepository->findSorted($data);
    }

    public function getBirdsCount(string $lang, string $where): int
    {
        return $this->birdFinderRepository->getBirdsCount($lang, $where);
    }

    public function findByOrderId(int $orderId): array
    {
        return $this->birdFinderRepository->findByOrderId($orderId);
    }
}