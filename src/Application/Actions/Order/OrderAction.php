<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use App\Application\Actions\Action;
use App\Domain\Order\OrderRepository;
use Psr\Log\LoggerInterface;

abstract class OrderAction extends Action
{
    protected OrderRepository $orderRepository;

    public function __construct(LoggerInterface $logger, OrderRepository $orderRepository)
    {
        parent::__construct($logger);
        $this->orderRepository = $orderRepository;
    }
}
