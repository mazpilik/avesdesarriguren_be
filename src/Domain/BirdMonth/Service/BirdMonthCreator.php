<?php

namespace App\Domain\BirdMonth\Service;

use App\Domain\BirdMonth\Repository\BirdMonthCreatorRepository;

final class BirdMonthsCreator
{
    private BirdMonthCreatorRepository $birdMonthCreatorRepository;

    public function __construct(BirdMonthCreatorRepository $birdMonthCreatorRepository)
    {
        $this->birdMonthCreatorRepository = $birdMonthCreatorRepository;
    }

    public function create(string $bird_id, int $month, string $content): string
    {
        return $this->birdMonthCreatorRepository->create($bird_id, $month, $content);
    }
}