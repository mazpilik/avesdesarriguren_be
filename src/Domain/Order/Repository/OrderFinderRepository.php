<?php

namespace App\Domain\Order\Repository;

use PDO;

final class OrderFinderRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * get all orders without pagination
     */
    public function findAll(): array
    {
        $sth = $this->db->prepare('SELECT * FROM orders');
        $sth->execute();
        $orders = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    /**
     * sort orders by id or by name and paginated
     * 
     * @param int $page
     * @param int $limit
     * @param string $orderBy
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

        $sql = "SELECT * FROM orders $where_condition ORDER BY $orderby $direction LIMIT $limit OFFSET $offset";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $orders;
    }

    /**
     * get order count
     */
    public function getOrderCount(string $where): int
    {
        $where_condition = '';
        if(!empty($where)){
          $where_value = "%$where%";
          $where_condition = ' WHERE name LIKE :name';
        }

        $sth = $this->db->prepare('SELECT COUNT(*) FROM orders'.$where_condition);
        if(!empty($where)){
            $sth->bindParam(':name', $where_value, PDO::PARAM_STR);
        }
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    /**
     * get order by id
     * 
     * @param int $id
     * @return array $order
    */
    public function findByid(int $id): array
    {
        try {
            $sth = $this->db->prepare('SELECT * FROM orders WHERE id = :id');
            $sth->bindParam(':id', $id);
            $sth->execute();
            $order = $sth->fetch(PDO::FETCH_ASSOC);
            if($order){
                return $order;
            } else {
                return ['NOT_FOUND'];
            }
        } catch (PDOException $e) {
            return ['FIND_FAIL'];
        }
    }
}