<?php

namespace App\Domain\News\Service;

use App\Domain\News\Repository\NewsUpdaterRepository;
use App\Domain\News\Repository\NewsFinderRepository;


final class NewsUpdater
{
    private NewsUpdaterRepository $newsUpdaterRepository;
    private NewsFinderRepository $newsFinderRepository;

    public function __construct(
        NewsUpdaterRepository $newsUpdaterRepository,
        NewsFinderRepository $newsFinderRepository, 
    )
    {
        $this->newsUpdaterRepository = $newsUpdaterRepository;
        $this->newsFinderRepository = $newsFinderRepository;
    }

    public function update(int $id, array $news_data): string
    {
        // update news data
        $this->newsUpdaterRepository->updateNews($id, date("Y-m-d"));

        // update additional data
        $additional_data = [];
        foreach($news_data['newsData'] as $ad_by_language) {
            $news_additional_data = [
                'news_id' => $id,
                'lang' => $ad_by_language['lang'],
                'title' => $ad_by_language['title'],
                'subtitle' => $ad_by_language['subtitle'],
                'body' => $ad_by_language['body'],
            ];
            $additional_data[] = $news_additional_data;
        }
        $this->newsUpdaterRepository->updateAdditionalData($id, $additional_data);

        // remove image if is empty
        if(empty($news_data['img'])) {
          // get images from db
          $image_in_db = $this->newsFinderRepository->findImagesByNewsId($id);
          
          // delete images
          $this->newsUpdaterRepository->deleteImage($id);

          // remove image from server folder public/images/news
          $image_path = './images/news/'.$image_in_db['img'];
          if(file_exists($image_path)) {
              unlink($image_path);
          }
        }

        // return success message
        return 'SUCCESS_UPDATED';

        
    }
}