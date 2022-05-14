<?php

declare (strict_types=1);

namespace App\Application\Actions\News;

// use request and response
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use NewsDeleter;
use App\Domain\News\Service\NewsDeleter;

final class DeleteNewsAction
{
    private $newsDeleter;

    public function __construct(NewsDeleter $newsDeleter)
    {
        $this->newsDeleter = $newsDeleter;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
      try {
        $newsId = (int) $args['id'];
        $this->newsDeleter->delete($newsId);
        $response->getBody()->write(json_encode(['SUCCESS_DELETE']));
        return $response;
      } catch (\Throwable $th) {
        $error_message = 'ERROR_DELETE';
        $error_message = $th->getMessage();
        $response->getBody()->write(json_encode($error_message));
        $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus(500);
      }
        
    }
}