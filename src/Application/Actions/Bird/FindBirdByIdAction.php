<?php

declare (strict_types = 1);

namespace App\Application\Actions\Bird;

// get response and request from Slim
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Bird\Service\BirdFinder;

final class FindBirdByIdAction
{
    private BirdFinder $birdFinder;

    public function __construct(BirdFinder $birdFinder)
    {
        $this->birdFinder = $birdFinder;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {

        try {
            $id = (int) $args['id'];
            $bird = $this->birdFinder->findByid($id);

            $response->getBody()->write(json_encode($bird));

            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Throwable $th) {
            // get status code
            $status_code = $th->getCode();
            $error_message = $th->getMessage();

            $response->getBody()->write(json_encode($error_message));
            $response->withHeader('Content-Type', 'application/json');
            return $response->withStatus(500);
        }
    }
}