<?php

namespace App\Domain\News\Service;

use App\Domain\News\Repository\NewsDeleteRepository;
use App\Domain\News\Repository\NewsFinderRepository;

final class NewsDeleter
{
    private NewsDeleteRepository $newsDeleteRepository;
    private NewsFinderRepository $newsFinderRepository;

    public function __construct(NewsDeleteRepository $newsDeleteRepository, NewsFinderRepository $newsFinderRepository)
    {
        $this->newsDeleteRepository = $newsDeleteRepository;
        $this->newsFinderRepository = $newsFinderRepository;
    }

    public function delete(int $id): string
    {
      // get related images from find repository
      $images = $this->newsFinderRepository->findImagesByNewsId($id);
      
      $response =  $this->newsDeleteRepository->delete($id);

      // delete all images related to this news
      if(isset($images['img'])){
        $image_path = './images/news/'.$images['img'];
        if(file_exists($image_path)) {
            unlink($image_path);
        }
      }

      return $response;
    }
}