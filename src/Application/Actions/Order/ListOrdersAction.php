<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use Psr\Http\Message\ResponseInterface as Response;

class ListOrdersAction extends OrderAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $orders = $this->orderRepository->findAll();

        $this->logger->info("Orders list was viewed.");

        return $this->respondWithData($orders);
    }
}
