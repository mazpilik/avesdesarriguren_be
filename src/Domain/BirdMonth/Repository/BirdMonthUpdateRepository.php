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

    public function update($id, int $birdId, int $month, string $content_es, string $content_eus): void
    {
        $stmt = $this->pdo->prepare('UPDATE bird_month SET bird_id = :bird_id, p_month = :p_month, content_es = :content_es, content_eus = :content_eus WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':bird_id', $birdId, PDO::PARAM_INT);
        $stmt->bindValue(':p_month', $month, PDO::PARAM_INT);
        $stmt->bindValue(':content_es', $content_es, PDO::PARAM_STR);
        $stmt->bindValue(':content_eus', $content_eus, PDO::PARAM_STR);
        $stmt->execute();
    }
}