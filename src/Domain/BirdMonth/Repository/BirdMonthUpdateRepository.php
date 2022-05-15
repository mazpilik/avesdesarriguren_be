<?php

declare (strict_types = 1);

namespace App\Domain\BirdMonth\Repository;

use PDO;

final class BirdMonthUpdateRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function update(int $birdId, int $month, string $content): void
    {
        $stmt = $this->pdo->prepare('UPDATE bird_month SET content = :content WHERE bird_id = :bird_id AND p_month = :p_month');
        $stmt->bindValue(':bird_id', $birdId, PDO::PARAM_INT);
        $stmt->bindValue(':p_month', $month, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
    }
}