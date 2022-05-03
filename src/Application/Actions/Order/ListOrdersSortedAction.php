<?php

declare (strict_types = 1);

namespace App\Application\Actions\Order;

use App\Domain\Order\Service\OrdersFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ListOrdersSortedAction
{
    private OrdersFinder $ordersFinder;

    public function __construct(OrdersFinder $ordersFinder)
    {
        $this->ordersFinder = $ordersFinder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = [];
        $data['page'] = $request->getAttribute('page');
        $data['limit'] = $request->getAttribute('limit');
        $data['orderby'] = $request->getAttribute('orderby') === 'date' ? 'id' : 'name';
        $data['direction'] = $request->getAttribute('direction');
        $data['where'] = $request->getAttribute('where');

        $orders = $this->ordersFinder->findSorted($data);

        $response->getBody()->write(json_encode($orders));
        return $response->withHeader('Content-Type', 'application/json');
    }
}