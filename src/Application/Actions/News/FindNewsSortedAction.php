<?php

declare (strict_types = 1);

namespace App\Application\Actions\News;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\News\Service\NewsFinder;

final class FindNewsSortedAction
{
    private NewsFinder $newsFinder;

    public function __construct(NewsFinder $newsFinder)
    {
        $this->newsFinder = $newsFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
      
        try {
            $data = [];
            $data['lang'] = $request->getAttribute('lang');
            $data['page'] = $request->getAttribute('page');
            $data['limit'] = $request->getAttribute('limit');
            $data['orderby'] = $request->getAttribute('orderby') === 'date' ? 'id' : 'title';
            $data['direction'] = $request->getAttribute('direction');
            $data['where'] = $request->getAttribute('where');

            $news = $this->newsFinder->findSorted($data);

            $response->getBody()->write(json_encode($news));

            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Throwable $th) {
            // get status code
            $status_code = $th->getCode();
            $error_mesage = $th->getMessage();

            $response->getBody()->write(json_encode($error_mesage));
            $response->withHeader('Content-Type', 'application/json');
            return $response->withStatus(500);
        }
    }
}