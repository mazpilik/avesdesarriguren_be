<?php

namespace App\Domain\Family\Service;

use App\Domain\Family\Repository\FamilyDeleteRepository;

final class FamilyDeleter
{
    private FamilyDeleteRepository $familyDeleteRepository;

    public function __construct(FamilyDeleteRepository $familyDeleteRepository)
    {
        $this->familyDeleteRepository = $familyDeleteRepository;
    }

    public function delete(int $id): string
    {
        return $this->familyDeleteRepository->delete($id);
    }
}