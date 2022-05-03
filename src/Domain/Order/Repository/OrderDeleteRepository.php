<?php

namespace App\Domain\Order\Repository;

use PDO;

final class OrderDeleteRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function delete(int $id): string
    {
        try {
            $sth = $this->db->prepare('DELETE FROM orders WHERE id = :id');
            $sth->bindParam('id', $id, PDO::PARAM_INT);
            $sth->execute();

            return 'DELETE_SUCCESS';

        } catch (PDOException $e) {

            return 'DELETE_FAILED';

        }
    }
}