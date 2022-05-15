<?php

declare (strict_types = 1);

use PDO;

final class BirdMonthFinderRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findLast(): array
    {
        $sql = 'SELECT * FROM bird_month ORDER BY id DESC LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}