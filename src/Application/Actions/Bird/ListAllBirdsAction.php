<?php

  declare (strict_types = 1);

  namespace App\Application\Actions\Bird;

  // request and response
  use Psr\Http\Message\ResponseInterface as Response;
  use Psr\Http\Message\ServerRequestInterface as Request;

  // use BirdFinder
  use App\Domain\Bird\Service\BirdFinder;

  final class ListAllBirdsAction
  {
    /**
     * @var BirdFinder
     */
    private $birdFinder;

    /**
     * @param BirdFinder $birdFinder
     */
    public function __construct(BirdFinder $birdFinder)
    {
      $this->birdFinder = $birdFinder;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
      $birds = $this->birdFinder->findAll();

      $response->getBody()->write(
        json_encode($birds)
      );

      return $response->withHeader('Content-Type', 'application/json');
    }
  }