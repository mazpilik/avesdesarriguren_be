<?php

declare (strict_types = 1);

namespace App\Application\Actions\Bird;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Bird\Service\BirdUPdater;

final class UpdateBirdAction
{
    private BirdUpdater $birdUpdater;

    public function __construct(BirdUpdater $birdUpdater)
    {
        $this->birdUpdater = $birdUpdater;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
      try {
        $id = (int) $args['id'];
        $bird_data = $request->getParsedBody();
        
        $bird = $this->birdUpdater->update($id, $bird_data);

        $response->getBody()->write(json_encode($bird));

        return $response->withHeader('Content-Type', 'application/json');
      } catch (\Throwable $th) {
        $response->getBody()->write(json_encode($th->getMessage()));
        // $response->getBody()->write(json_encode('ERROR_UPDATE'));
        $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus(500);
      }
        
    }
}