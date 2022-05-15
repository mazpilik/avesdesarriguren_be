<?php

declare (strict_types = 1);

namespace App\Domain\BirdMonth\Service;

use App\Domain\BirdMonth\Repository\BirdMonthFinderRepository;

final class BirdMonthFinder
{
    private BirdMonthFinderRepository $birdMonthFinderRepository;

    public function __construct(BirdMonthFinderRepository $birdMonthFinderRepository)
    {
        $this->birdMonthFinderRepository = $birdMonthFinderRepository;
    }

    public function findLast(): array
    {
        return $this->birdMonthFinderRepository->findLast();
    }
}