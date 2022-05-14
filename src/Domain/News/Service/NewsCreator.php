<?php

declare(strict_types=1);

namespace App\Domain\News\Service;

use App\Domain\News\Repository\NewsCreateRepository;

final class NewsCreator
{
    private $newsCreateRepository;

    public function __construct(NewsCreateRepository $newsCreateRepository)
    {
        $this->newsCreateRepository = $newsCreateRepository;
    }

    public function createNews(array $data) : int
    {
        // create a news in news table
        $user_id = (int) $data['userId'];
        $news_id = $this->newsCreateRepository->create($user_id);
        
        //bind news data to news´
        $request_news_data = $data['newsData'];
        $news_data = [];

        foreach ($request_news_data as $news_data_item) {
          // add news_id to newsData_item
          $news_data_item['news_id'] = $news_id;
          $news_data[] = $news_data_item;
        }

        // create news data in news_data table
        $this->newsCreateRepository->createNewsData($news_data);
        
        return $news_id;
    }

    // check if new exists
    public function checkNewsExists(int $bird_id): bool
    {
        return $this->newsCreateRepository->checkNewsExists($bird_id);
    }

    // bind image to bird
    public function bindImage(int $new_id, string $filename): void
    {
        $this->newsCreateRepository->bindImage($new_id, $filename);
    }
}