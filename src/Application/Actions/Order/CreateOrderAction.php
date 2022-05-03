<?php

declare (strict_types = 1);

namespace App\Application\Actions\Order;

use App\Domain\Order\Service\OrdersCreator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CreateOrderAction
{
    private OrdersCreator $ordersCreator;

    public function __construct(OrdersCreator $ordersCreator)
    {
        $this->ordersCreator = $ordersCreator;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $return_message = $this->ordersCreator->create($data['name']);
        
        $response->getBody()->write(json_encode($return_message));

        return $response->withHeader('Content-Type', 'application/json');
    }
}