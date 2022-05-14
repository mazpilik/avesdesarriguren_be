<?php

declare(strict_types=1);

namespace App\Application\Actions\News;

// request and response
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use NewsCreator
use App\Domain\News\Service\NewsCreator;

final class CreateNewsAction
{
    private $newsCreator;

    public function __construct(NewsCreator $newsCreator)
    {
        $this->newsCreator = $newsCreator;
    }

    public function __invoke(Request $request, Response $response): Response
    {
      try {
        $data = $request->getParsedBody();
        $newsId = $this->newsCreator->createNews($data);
        $response->getBody()->write(json_encode($newsId));
        return $response->withStatus(201);
      } catch (\Throwable $th) {
        $response->getBody()->write(json_encode($th->getMessage()));
        return $response->withStatus(500);
      }
    }
}