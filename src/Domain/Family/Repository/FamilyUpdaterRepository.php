<?php

namespace App\Domain\Family\Repository;

use PDO;

final class FamilyUpdaterRepository
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
    public function update(int $id, int $order_id, string $name): string
    {
      try {
        $sth = $this->db->prepare('UPDATE family SET order_id = :order_id, name = :name WHERE id = :id');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->bindParam('order_id', $order_id, PDO::PARAM_INT);
        $sth->bindParam('name', $name, PDO::PARAM_STR);
        $sth->execute();

        return 'UPDATE_SUCCESS';

      } catch (PDOException $e) {
        
        return 'UPDATE_FAILED';
      
      }
    }
}