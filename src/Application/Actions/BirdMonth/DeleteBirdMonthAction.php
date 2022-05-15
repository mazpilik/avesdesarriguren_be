<?php

declare (strict_types = 1);

namespace App\Application\Actions\BirdMonth;

// use response and request
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// user BirdMonthDeleter service
use App\Domain\BirdMonth\Service\BirdMonthDeleter;

final class DeleteBirdMonthAction
{
    private BirdMonthDeleter $birdMonthDeleter;

    public function __construct(BirdMonthDeleter $birdMonthDeleter)
    {
        $this->birdMonthDeleter = $birdMonthDeleter;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];

        $this->birdMonthDeleter->delete($id);
        return $response->withStatus(204);
    }
}