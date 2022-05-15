<?php

namespace App\Domain\BirdMonth\Service;

use App\Domain\BirdMonth\Repository\BirdMonthCreatorRepository;

final class BirdMonthCreator
{
    private BirdMonthCreatorRepository $birdMonthCreatorRepository;

    public function __construct(BirdMonthCreatorRepository $birdMonthCreatorRepository)
    {
        $this->birdMonthCreatorRepository = $birdMonthCreatorRepository;
    }

    public function create(int $bird_id, int $month, string $content_es, string $content_eus): string
    {
        return $this->birdMonthCreatorRepository->create($bird_id, $month, $content_es, $content_eus);
    }
}