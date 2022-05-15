<?php

declare (strict_types = 1);

namespace App\Domain\BirdMonth\Service;

use App\Domain\BirdMonth\Repository\BirdMonthDeleterRepository;

final class BirdMonthDeleter
{
    private BirdMonthDeleterRepository $birdMonthDeleterRepository;

    public function __construct(BirdMonthDeleterRepository $birdMonthDeleterRepository)
    {
        $this->birdMonthDeleterRepository = $birdMonthDeleterRepository;
    }

    public function delete(int $id): void
    {
        $this->birdMonthDeleterRepository->delete($id);
    }
}