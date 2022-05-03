<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use App\Domain\Order\Service\OrdersFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ListAllOrdersAction{
    private $orderRepository;

    public function __construct(OrdersFinder $ordersFinder)
    {
        $this->ordersFinder = $ordersFinder;
    }
    public function __invoke(Request $request, Response $response): Response
    {
        $orders = $this->ordersFinder->findAll();
        
        $response->getBody()->write(json_encode($orders));
        return $response->withHeader('Content-Type', 'application/json');
    }
};
