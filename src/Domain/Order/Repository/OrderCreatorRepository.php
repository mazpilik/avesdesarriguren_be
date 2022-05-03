<?php

namespace App\Domain\Order\Repository;

use PDO;

final class OrderCreatorRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $name): string
    {
        try{
            $sql = "INSERT INTO orders (name) VALUES (:name)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return 'CREATE_SUCCESS';
        }catch(PDOException $e){
            $status = $e->getCode();
            if($status == 23000) {

                return 'CREATE_ERROR_DUPLICATED';

            } else {

                return 'CREATE_ERROR';
            }
        }
    }
}