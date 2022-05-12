<?php

namespace App\Domain\Bird\Repository;

use PDO;
use App\Domain\Bird\Data\BirdAditionalData;

final class BirdCreatorRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $family_id, string $name): string
    {
        // prepare the query
        $sql = "INSERT INTO bird (family_id, name) VALUES (:family, :name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':family', $family_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        
        // execute the query and return the bird id
        $stmt->execute();
        return $this->pdo->lastInsertId();
        
    }

    public function additionalData(iterable $data): void
    {
        $sql = "INSERT INTO bird_data (
        bird_id, name, summary, bird_length, wingspan, identification, 
        singing, moving, habitat, feeding, reproduction, population, 
        conservation_threats, world_distribution, peninsula_distribution, language) 
        VALUES (
        :bird_id, :name, :summary, :bird_length, :wingspan, :identification, 
        :singing, :moving, :habitat, :feeding, :reproduction, :population, 
        :conservation_threats, :world_distribution, :peninsula_distribution, :language)";

        $stmt = $this->pdo->prepare($sql);
        foreach($data as $row) {
            $stmt->execute($row);
        }

    }

    public function frecuency(iterable $data): void
    {
        $sql = "INSERT INTO bird_frecuency (bird_id, frecuency_id) VALUES (:bird_id, :frecuency_id)";
        $stmt = $this->pdo->prepare($sql);
        
        foreach($data as $row) {
            $stmt->execute($row);
        }
    }

    public function presenceMonths(iterable $data): void
    {
        $sql = "INSERT INTO bird_presence_months (bird_id, p_month) VALUES (:bird, :p_month)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $row) {
            $stmt->execute($row);
        }
    }

    /**
     * bind img and bird in bird_image table
     * 
     * @param img_name
     * @param bird_id
     * @return void
     * @throws \Exception
     * 
     * */
    public function bindImage(int $bird_id, string $img_name): void
    {
        $sql = "INSERT INTO bird_img (bird_id, img) VALUES (:bird_id, :img)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':bird_id', $bird_id, PDO::PARAM_INT);
        $stmt->bindParam(':img', $img_name, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * check if bird exists
     * 
     * @param bird_id
     * @return bool
     * @throws \Exception
     */
    public function checkBirdExists(int $bird_id): bool
    {
        $sql = "SELECT * FROM bird WHERE id = :bird_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':bird_id', $bird_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}