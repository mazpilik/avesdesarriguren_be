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
        $bird = $this->birdFinderRepository->findBasicInfoByid($id);

        // get Bird data
        $bird['birdData'] = $this->birdFinderRepository->findDataByid($id);

        // get bird frequency
        $frecuency = $this->birdFinderRepository->findFrecuencyByBirdId($id);
        $bird['frecuency'] = [];
        foreach ($frecuency as $value) {
            $bird['frecuency'][] = $value['frecuencyName'];
        }

        // get months
        $months = $this->birdFinderRepository->findMonthByBirdId($id);
        $bird['months'] = [];
        foreach ($months as $value) {
            $bird['months'][] = $value['p_month'];
        }

        // get bird images
        $bird['images'] = $this->birdFinderRepository->findImagesByBirdId($bird['id'],0);
        
        return $bird;

    }

    public function findSorted(array $data): array
    {
        $birds = $this->birdFinderRepository->findSorted($data);
        $birds_images = array();
        
        // get related imgages for each bird
        foreach ($birds as $bird) {
            $bird['images'] = $this->birdFinderRepository->findImagesByBirdId($bird['id'],1);
            $birds_images[] = $bird;
        }

        return $birds_images;
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