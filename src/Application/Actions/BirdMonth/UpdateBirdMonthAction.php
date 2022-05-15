<?php

declare (strict_types = 1);

namespace App\Application\Actions\BirdMonth;

// use response and request
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use BirdMonthUpdater service
use App\Domain\BirdMonth\Service\BirdMonthUpdater;

final class UpdateBirdMonthAction
{
    private BirdMonthUpdater $birdMonthUpdater;

    public function __construct(BirdMonthUpdater $birdMonthUpdater)
    {
        $this->birdMonthUpdater = $birdMonthUpdater;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $data = $request->getParsedBody();
        $bird_id = (int) $data['birdId'];
        $month = (int) $data['month'];
        $content_es = $data['contentEs'];
        $content_eus = $data['contentEus'];

        $this->birdMonthUpdater->update($id, $bird_id, $month, $content_es, $content_eus);
        return $response->withStatus(204);
    }
}