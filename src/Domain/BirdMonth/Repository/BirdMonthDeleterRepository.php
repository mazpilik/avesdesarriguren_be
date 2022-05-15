<?php

declare (strict_types = 1);

namespace App\Domain\BirdMonth\Repository;

use PDO;

final class BirdMonthDeleterRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM bird_month WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}