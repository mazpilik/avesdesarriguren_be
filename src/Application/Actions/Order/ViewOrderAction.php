<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use Psr\Http\Message\ResponseInterface as Response;

class ViewOrderAction extends OrderAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $orderId = (int) $this->resolveArg('id');
        $order = $this->orderRepository->findOrderOfId($orderId);

        $this->logger->info("Order of id `${orderId}` was viewed.");

        return $this->respondWithData($order);
    }
}
