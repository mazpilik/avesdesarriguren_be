<?php

namespace App\Domain\Family\Service;

use App\Domain\Family\Repository\FamilyCreatorRepository;

final class FamilyCreator
{
    private FamilyCreatorRepository $familyCreatorRepository;

    public function __construct(FamilyCreatorRepository $familyCreatorRepository)
    {
        $this->familyCreatorRepository = $familyCreatorRepository;
    }

    public function create(int $order_id, string $name): string
    {
        return $this->familyCreatorRepository->create($order_id, $name);
    }
}