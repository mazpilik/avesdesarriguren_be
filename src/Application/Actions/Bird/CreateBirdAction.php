<?php

declare (strict_types = 1);

namespace App\Application\Actions\Bird;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Bird\Service\BirdCreator;

final class CreateBirdAction
{
    private BirdCreator $birdCreator;

    public function __construct(BirdCreator $birdCreator)
    {
        $this->birdCreator = $birdCreator;
    }

    public function __invoke(Request $request, Response $response): Response
    {

        try {
          $data = $request->getParsedBody();

          $bird_id = $this->birdCreator->create($data);

          $response->getBody()->write($bird_id);

          return $response->withHeader('Content-Type', 'application/json');
          
      } catch (\Throwable $th) {
          // get status code
          $status_code = $th->getCode();
        //   $error_message = 'ERROR_CREATE';
          $error_message = $th->getMessage();
          if($status_code == 23000){
              $error_message = 'ERROR_DUPLICATE_ENTRY';
          }

          $response->getBody()->write(json_encode($error_message));
          $response->withHeader('Content-Type', 'application/json');
          return $response->withStatus(500);
      }
    }

}