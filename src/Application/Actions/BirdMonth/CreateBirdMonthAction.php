<?php

declare(strict_types=1);

namespace App\Application\Actions\BirdMonth;

// request and response
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// use BirdMonthCreator
use App\Domain\BirdMonth\Service\BirdMonthCreator;

final class CreateBirdMonthAction
{
    private $birdMonthCreator;

    public function __construct(BirdMonthCreator $birdMonthCreator)
    {
        $this->birdMonthCreator = $birdMonthCreator;
    }

    public function __invoke(Request $request, Response $response): Response
    {
      try {
        $data = $request->getParsedBody();
        $bird_id = (int) $data['birdId'];
        $month = (int) $data['month'];
        $content_es = $data['contentEs'];
        $content_eus = $data['contentEus'];
        $birdMonthId = $this->birdMonthCreator->create($bird_id, $month, $content_es, $content_eus);
        $response->getBody()->write(json_encode($birdMonthId));
        return $response->withStatus(201);
      } catch (\Throwable $th) {
        $response->getBody()->write(json_encode($th->getMessage()));
        return $response->withStatus(500);
      }
    }
}