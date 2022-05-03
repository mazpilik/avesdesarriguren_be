<?php

declare (strict_types = 1);

namespace App\Application\Actions\Order;

use App\Domain\Order\Service\OrdersUpdater;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UpdateOrderAction
{
    private OrdersUpdater $ordersUpdater;

    public function __construct(OrdersUpdater $ordersUpdater)
    {
        $this->ordersUpdater = $ordersUpdater;
    }

    public function __invoke(Request $request, Response $response): Response
    {
      $request_body = $request->getParsedBody();
      $id = (int)$request->getAttribute('id');

        $resMessage = $this->ordersUpdater->update($id, $request_body['name']);

        $response->getBody()->write(json_encode($resMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
}