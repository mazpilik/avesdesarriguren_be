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

    public function delete(int $birdId, int $month): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM bird_month WHERE bird_id = :bird_id AND p_month = :p_month');
        $stmt->bindValue(':bird_id', $birdId, PDO::PARAM_INT);
        $stmt->bindValue(':p_month', $month, PDO::PARAM_INT);
        $stmt->execute();
    }
}