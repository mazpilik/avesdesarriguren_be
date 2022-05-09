<?php

declare (strict_types = 1);

namespace App\Application\Actions\Bird;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Bird\Service\BirdFinder;

final class FindBirdsSortedAction
{
    private BirdFinder $birdFinder;

    public function __construct(BirdFinder $birdFinder)
    {
        $this->birdFinder = $birdFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
      
        try {
            $data = [];
            $data['lang'] = $request->getAttribute('lang');
            $data['page'] = $request->getAttribute('page');
            $data['limit'] = $request->getAttribute('limit');
            $data['orderby'] = $request->getAttribute('orderby') === 'date' ? 'id' : 'name';
            $data['direction'] = $request->getAttribute('direction');
            $data['where'] = $request->getAttribute('where');

            $birds = $this->birdFinder->findSorted($data);

            $response->getBody()->write(json_encode($birds));

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