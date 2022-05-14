<?php

namespace App\Domain\News\Service;

use App\Domain\News\Repository\NewsFinderRepository;

final class NewsFinder
{
    private NewsFinderRepository $newsFinderRepository;

    public function __construct(NewsFinderRepository $newsFinderRepository)
    {
        $this->newsFinderRepository = $newsFinderRepository;
    }

    public function findAll(): array
    {
        return $this->newsFinderRepository->findAll();
    }

    public function findByid(int $id): array
    {
        $news = $this->newsFinderRepository->findNewsByid($id);

        // get News data
        $news['newsData'] = $this->newsFinderRepository->findDataByid($id);
        
        return $news;

    }

    public function findSorted(array $data): array
    {
        $news = $this->newsFinderRepository->findSorted($data);

        return $news;
    }

    public function getNewsCount(string $lang, string $where): int
    {
        return $this->newsFinderRepository->getNewsCount($lang, $where);
    }
}