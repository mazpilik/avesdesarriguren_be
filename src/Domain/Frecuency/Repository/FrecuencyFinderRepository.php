<?php

namespace App\Domain\Frecuency\Repository;

use PDO;

class FrecuencyFinderRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM frecuency";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * find frecuency by name
     * 
     * @param string $name
     * @return array $frecuency
     */
    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM frecuency WHERE name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}