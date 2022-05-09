<?php

declare (strict_types = 1);

namespace App\Application\Actions\Bird;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Bird\Service\BirdFinder;

final class GetBirdsCountAction
{
    private BirdFinder $birdFinder;

    public function __construct(BirdFinder $birdFinder)
    {
        $this->birdFinder = $birdFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $lang = $request->getAttribute('lang');
        $where = '';
        if ($request->getAttribute('where') !== null) {
          $where = $request->getAttribute('where');
        }

        
        $count = $this->birdFinder->getBirdsCount($lang, $where);

        $response->getBody()->write(json_encode($count));
        return $response->withHeader('Content-Type', 'application/json');

    }
}