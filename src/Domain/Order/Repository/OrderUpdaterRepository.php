<?php

namespace App\Domain\Order\Repository;

use PDO;

final class OrderUpdaterRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * update order
     * 
     * @param array $data
     * @return Response
     */
    public function update(int $id, string $name): string
    {
      try {
        $sth = $this->db->prepare('UPDATE orders SET name = :name WHERE id = :id');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->bindParam('name', $name, PDO::PARAM_STR);
        $sth->execute();

        return 'UPDATE_SUCCESS';

      } catch (PDOException $e) {
        
        return 'UPDATE_FAILED';
      
      }
    }
}