<?php

namespace App\Domain\Family\Service;

use App\Domain\Family\Repository\FamilyUpdaterRepository;

final class FamilyUpdater
{
    private FamilyUpdaterRepository $familyUpdaterRepository;

    public function __construct(FamilyUpdaterRepository $familyUpdaterRepository)
    {
        $this->familyUpdaterRepository = $familyUpdaterRepository;
    }

    public function update(int $id, int $order_id, string $name): string
    {
        return $this->familyUpdaterRepository->update($id, $order_id, $name);
    }
}