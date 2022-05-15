<?php

declare (strict_types = 1);

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
        $bird_id =(int) $request->getAttribute('birdId');
        $month =(int) $request->getAttribute('month');
        $data = $request->getParsedBody();
        $content = $data['content'];

        $this->birdMonthUpdater->update($bird_id, $month, $content);
        return $response->withStatus(204);
    }
}