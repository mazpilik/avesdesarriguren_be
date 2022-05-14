<?php

declare (strict_types=1);

namespace App\Application\Actions\Bird;

// use request and response
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use BirdDeleter;
use App\Domain\Bird\Service\BirdDeleter;

final class DeleteBirdAction
{
    private $birdDeleter;

    public function __construct(BirdDeleter $birdDeleter)
    {
        $this->birdDeleter = $birdDeleter;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
      try {
        $birdId = (int) $args['id'];
        $this->birdDeleter->delete($birdId);
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