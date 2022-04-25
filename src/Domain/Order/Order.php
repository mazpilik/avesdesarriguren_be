<?php

declare(strict_types=1);

namespace App\Domain\Order;

use JsonSerializable;

class Order implements JsonSerializable
{
    private ?int $id;

    private string $name;

    public function __construct(?int $id, string $name)
    {
        $this->id = $id;
        $this->name = strtolower($name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
