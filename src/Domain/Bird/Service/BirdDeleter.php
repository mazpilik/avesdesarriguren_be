<?php

namespace App\Domain\Bird\Service;

use App\Domain\Bird\Repository\BirdDeleteRepository;
use App\Domain\Bird\Repository\BirdFinderRepository;

final class BirdDeleter
{
    private BirdDeleteRepository $birdDeleteRepository;
    private BirdFinderRepository $birdFinderRepository;

    public function __construct(BirdDeleteRepository $birdDeleteRepository, BirdFinderRepository $birdFinderRepository)
    {
        $this->birdDeleteRepository = $birdDeleteRepository;
        $this->birdFinderRepository = $birdFinderRepository;
    }

    public function delete(int $id): string
    {
        // get related images from find repository
        $images = $this->birdFinderRepository->findImagesByBirdId($id);
        
         $response =  $this->birdDeleteRepository->delete($id);

        // delete all images related to this bird
        foreach ($images as $image) {
            $image_path = './images/birds/'.$image['img'];
            if(file_exists($image_path)) {
                unlink($image_path);
            }
        }

        return $response;
    }
}