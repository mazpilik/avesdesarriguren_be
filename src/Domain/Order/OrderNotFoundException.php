<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\DomainException\DomainRecordNotFoundException;

class OrderNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Order you requested does not exist.';
}
