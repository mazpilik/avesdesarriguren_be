<?php

namespace App\Domain\Bird\Repository;

use PDO;

final class BirdUpdaterRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * update bird
     * 
     * @param int $id
     * @param int $family_id
     * @param string $name
     * @return void
     */
    public function updateBird(int $id, int $family_id, string $name): void
    {
        $sql = "UPDATE bird SET family_id = :family_id, name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':family_id', $family_id);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
    }

    /**
     * update bird data
     * 
     * @param int $id
     * @param array $additional_data_array
     * @return void
     * @throws \Exception
     * 
     */
    public function updateAdditionalData(int $id, iterable $additional_data_array): void
    {
      $sql = "UPDATE bird_data 
      SET 
      name = :name, 
      summary = :summary, 
      bird_length = :bird_length, 
      wingspan = :wingspan, 
      identification = :identification,
      singing = :singing,
      moving = :moving,
      habitat = :habitat,
      feeding = :feeding,
      reproduction = :reproduction,
      population = :population,
      conservation_threats = :conservation_threats,
      world_distribution = :world_distribution,
      peninsula_distribution = :peninsula_distribution 
      WHERE bird_id = :bird_id AND language = :language";
      $stmt = $this->db->prepare($sql);
      
      foreach ($additional_data_array as $row) {
        $stmt->execute($row);
      }
    }

    /**
     * delete bird frecuency
     * 
     * @param array $frecuencies
     * 
     */
    public function deleteFrecuency(iterable $frecuencies): void
    {
        $sql = "DELETE FROM bird_frecuency WHERE bird_id = :bird_id AND frecuency_id = :frecuency_id";
        $stmt = $this->db->prepare($sql);
        foreach ($frecuencies as $row) {
            $stmt->execute($row);
        }
    }

    /**
     * delete bird presence month
     * 
     * @param array $presence_months
     * 
     */
    public function deletePresenceMonth(iterable $presence_months): void
    {
        $sql = "DELETE FROM bird_month WHERE bird_id = :bird_id AND p_month = :p_month";
        $stmt = $this->db->prepare($sql);
        foreach ($presence_months as $row) {
            $stmt->execute($row);
        }
    }
}