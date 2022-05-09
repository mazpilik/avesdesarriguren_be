<?php
declare (strict_types = 1);

namespace App\Application\Actions\Family;

use App\Domain\Family\Service\FamilyFinder;
// use request and response classes
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class FindFamiliesByOrderId
{
  private FamilyFinder $familyFinder;

  public function __construct(FamilyFinder $familyFinder)
  {
      $this->familyFinder = $familyFinder;
  }
  public function __invoke(Request $request, Response $response, array $args)
  {
      $orderId = (int)$args['orderId'];
      $families = $this->familyFinder->findByOrderId($orderId);
      if($families[0] == 'NOT_FOUND') {
          $response->getBody()->write(json_encode($families));
          //with message not_found
          $response->withHeader('Content-Type', 'application/json');
          return $response->withStatus(404);
      }
      $response->getBody()->write(json_encode($families));
      return $response->withHeader('Content-Type', 'application/json');
  }
}