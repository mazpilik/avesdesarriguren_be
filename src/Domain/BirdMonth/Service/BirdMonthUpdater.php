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

    public function update(int $id, int $birdId, int $month, string $content_es, string $content_eus): void
    {
        $this->birdMonthUpdateRepository->update($id, $birdId, $month, $content_es, $content_eus);
    }
}