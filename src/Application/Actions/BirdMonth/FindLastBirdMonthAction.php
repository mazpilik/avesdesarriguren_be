<?php

declare (strict_types = 1);

namespace App\Application\Actions\BirdMonth;

use App\Domain\BirdMonth\Service\BirdMonthFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FindLastBirdMonthAction
{
    private BirdMonthFinder $birdMonthFinder;

    public function __construct(BirdMonthFinder $birdMonthFinder)
    {
        $this->birdMonthFinder = $birdMonthFinder;
    }
    public function __invoke(Request $request, Response $response): Response
    {
        $birdMonth = $this->birdMonthFinder->findLast();
        
        $response->getBody()->write(json_encode($birdMonth));
        return $response->withHeader('Content-Type', 'application/json');
    }
};