<?php

declare (strict_types = 1);

namespace App\Application\Actions\Order;

use App\Domain\Order\Service\OrdersFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FindOrderByIdAction
{
    private OrdersFinder $ordersFinder;

    public function __construct(OrdersFinder $ordersFinder)
    {
        $this->ordersFinder = $ordersFinder;
    }
    public function __invoke(Request $request, Response $response): Response
    {
        $id = (int)$request->getAttribute('id');
        $order = $this->ordersFinder->findById($id);
        
        $response->getBody()->write(json_encode($order));
        return $response->withHeader('Content-Type', 'application/json');
    }
};