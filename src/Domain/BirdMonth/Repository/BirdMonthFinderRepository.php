<?php

declare (strict_types = 1);

namespace App\Domain\BirdMonth\Repository;

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
        $sql = "SELECT
        bm.id, 
        bm.p_month as month,
        bm.bird_id as birdId,
        b.name,
        bm.content_es,
        bm.content_eus
        FROM 
        bird_month bm
        JOIN bird b ON bm.bird_id = b.id 
        WHERE bm.p_month = (SELECT MAX(p_month) FROM bird_month)  
        ORDER BY bm.id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}