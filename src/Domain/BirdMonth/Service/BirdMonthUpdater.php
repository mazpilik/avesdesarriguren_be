<?php

declare (strict_types = 1);

namespace App\Domain\BirdMonth\Service;

use App\Domain\BirdMonth\Repository\BirdMonthUpdateRepository;

final class BirdMonthUpdater
{
    private BirdMonthUpdateRepository $birdMonthUpdateRepository;

    public function __construct(BirdMonthUpdateRepository $birdMonthUpdateRepository)
    {
        $this->birdMonthUpdateRepository = $birdMonthUpdateRepository;
    }

    public function update(int $birdId, int $month, string $content): void
    {
        $this->birdMonthUpdateRepository->update($birdId, $month, $content);
    }
}