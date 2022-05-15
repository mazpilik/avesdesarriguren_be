<?php

declare (strict_types = 1);

namespace App\Application\Actions\BirdMonth;

use App\Domain\BirdMonth\Service\BirdMonthsFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FindLastBirdMonthAction
{
    private BirdMonthsFinder $birdMonthsFinder;

    public function __construct(BirdMonthsFinder $birdMonthsFinder)
    {
        $this->birdMonthsFinder = $birdMonthsFinder;
    }
    public function __invoke(Request $request, Response $response): Response
    {
        $birdMonth = $this->birdMonthsFinder->findLast();
        
        $response->getBody()->write(json_encode($birdMonth));
        return $response->withHeader('Content-Type', 'application/json');
    }
};