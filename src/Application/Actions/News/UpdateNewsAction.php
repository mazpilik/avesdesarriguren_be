<?php

declare (strict_types = 1);

namespace App\Application\Actions\News;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\News\Service\NewsUPdater;

final class UpdateNewsAction
{
    private NewsUpdater $newsUpdater;

    public function __construct(NewsUpdater $newsUpdater)
    {
        $this->newsUpdater = $newsUpdater;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
      try {
        $id = (int) $args['id'];
        $news_data = $request->getParsedBody();
        
        $news = $this->newsUpdater->update($id, $news_data);

        $response->getBody()->write(json_encode($news));

        return $response->withHeader('Content-Type', 'application/json');
      } catch (\Throwable $th) {
        $response->getBody()->write(json_encode($th->getMessage()));
        // $response->getBody()->write(json_encode('ERROR_UPDATE'));
        $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus(500);
      }
        
    }
}