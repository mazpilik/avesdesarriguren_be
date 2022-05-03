<?php

declare (strict_types = 1);

namespace App\Application\Actions\Order;

use App\Domain\Order\Service\OrdersDeleter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OrderDeleteAction
{
    private OrdersDeleter $ordersDeleter;

    public function __construct(OrdersDeleter $ordersDeleter)
    {
        $this->ordersDeleter = $ordersDeleter;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $id = (int)$request->getAttribute('id');

        $resMessage = $this->ordersDeleter->delete($id);

        $response->getBody()->write(json_encode($resMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
}