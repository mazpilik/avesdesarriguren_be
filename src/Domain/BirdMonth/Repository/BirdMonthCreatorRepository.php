<?php

namespace App\Domain\BirdMonth\Repository;

use PDO;

final class BirdMonthCreatorRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $bird_id, int $month, string $content): string
    {
        try{
            $sql = "INSERT INTO 
            bird_month (bird_id, p_month, content) 
            VALUES (:bird_id, :p_month, :content)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':bird_id', $bird_id, PDO::PARAM_INT);
            $stmt->bindParam(':p_month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
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