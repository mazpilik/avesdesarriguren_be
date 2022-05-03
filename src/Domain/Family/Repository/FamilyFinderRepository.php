<?php

namespace App\Domain\Family\Repository;

use PDO;

final class FamilyFinderRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * get all family without pagination
     */
    public function findAll(): array
    {
        $sth = $this->db->prepare('SELECT * FROM family');
        $sth->execute();
        $families = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $families;
    }

    /**
     * sort family by id or by name and paginated
     * 
     * @param int $page
     * @param int $limit
     * @param string $familyBy
     * @param string $direction
     * @param string $where
     * @return Response
    */
    public function findSorted(array $data): array
    {
        $direction = strtoupper($data['direction']);
        $limit = $data['limit'];
        $orderby = $data['orderby'];
        $page = $data['page'];
        $where = $data['where'];

        $where_condition = '';
        $where_value = '';
        $offset = ($page - 1) * $limit;

        if(!empty($where)) {
            $where_value = '"%'.$where.'%"';
            $where_condition = "WHERE name LIKE $where_value";
        }

        $sql  = "SELECT family.id, family.name, family.order_id as orderId, orders.name as orderName FROM family ";
        $sql .= "JOIN orders ON (family.order_id = orders.id) ";
        $sql .= "$where_condition ORDER BY $orderby $direction LIMIT $limit OFFSET $offset";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        $families = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $families;
    }

    /**
     * get family count
     */
    public function getFamilyCount(string $where): int
    {
        $where_condition = '';
        if(!empty($where)){
          $where_value = "%$where%";
          $where_condition = ' WHERE name LIKE :name';
        }

        $sth = $this->db->prepare('SELECT COUNT(*) FROM family'.$where_condition);
        if(!empty($where)){
            $sth->bindParam(':name', $where_value, PDO::PARAM_STR);
        }
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    /**
     * get family by id
     * 
     * @param int $id
     * @return array $family
    */
    public function findByid(int $id): array
    {
        try {
            $sth = $this->db->prepare('SELECT family.id, family.name, family.order_id as orderId, orders.name as orderName FROM family JOIN orders ON (family.order_id = orders.id) WHERE family.id = :id');
            $sth->bindParam(':id', $id);
            $sth->execute();
            $family = $sth->fetch(PDO::FETCH_ASSOC);
            if($family){
                return $family;
            } else {
                return ['NOT_FOUND'];
            }
        } catch (PDOException $e) {
            return ['FIND_FAIL'];
        }
    }
}