<?php

namespace App\Domain\Family\Repository;

use PDO;

final class FamilyCreatorRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $order_id, string $name): string
    {
    
        $sql = "INSERT INTO family (order_id, name) VALUES (:order, :name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':order', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return 'CREATE_SUCCESS';
        
    }
}