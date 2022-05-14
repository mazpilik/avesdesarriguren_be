<?php

namespace App\Domain\Bird\Repository;

use PDO;

final class BirdFinderRepository
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
        $language = $data['lang'];

        $where_condition = "WHERE language = '$language'";
        $where_value = '';
        $offset = ($page - 1) * $limit;

        if(!empty($where)) {
            $where_value = '"%'.$where.'%"';
            $where_condition = $where_condition." AND name LIKE $where_value";
        }

        $sql  = "SELECT 
        bdata.bird_id as id, bdata.name, bdata.summary, bdata.bird_length as birdLength, 
        bdata.wingspan, bdata.identification, bdata.singing, bdata.moving, bdata.habitat, 
        bdata.feeding, bdata.reproduction, bdata.population, bdata.conservation_threats as conservationThreats, 
        bdata.world_distribution as worldDistribution, bdata.peninsula_distribution as peninsulaDistribution, bdata.language as lang, 
        family.id as familyId, 
        family.name as familyName, 
        orders.id as orderId, 
        orders.name as orderName 
        FROM bird_data bdata 
        JOIN bird ON (bird.id = bdata.bird_id) 
        JOIN family ON (family.id = bird.family_id) 
        JOIN orders ON (family.order_id = orders.id) 
        $where_condition 
        ORDER BY $orderby $direction 
        LIMIT $limit OFFSET $offset";

        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        $birds = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $birds;
    }

    /**
     * get family count
     */
    public function getBirdsCount(string $language, string $where): int
    {
        $where_condition = ' WHERE language = :language';
        if(!empty($where)){
          $where_value = "%$where%";
          $where_condition = ' AND name LIKE :name';
        }

        $sth = $this->db->prepare('SELECT COUNT(*) FROM bird_data'.$where_condition);
        if(!empty($where)){
            $sth->bindParam(':name', $where_value, PDO::PARAM_STR);
        }
        $sth->bindParam(':language', $language, PDO::PARAM_STR);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    /**
     * get bird basic info by id
     * 
     * @param int $id
     * @return array $bird
     */
    public function findBasicInfoByid(int $id): array
    {
        $sql = "SELECT 
        bird.id,
        bird.family_id as familyId,
        bird.name,
        family.name as familyName,
        family.order_id as orderId,
        orders.name as orderName
        FROM bird
        JOIN family ON (family.id = bird.family_id)
        JOIN orders ON (family.order_id = orders.id)
        WHERE bird.id = :id";

        $sth = $this->db->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $bird = $sth->fetch(PDO::FETCH_ASSOC);
        return $bird;
    }

    /**
     * get bird data by id
     * 
     * @param int $id
     * @return array $bird_data
     */
    public function findDataByid(int $id): array
    {
        $sql = "SELECT 
            bdata.name, bdata.summary, bdata.bird_length as birdLength, 
            bdata.wingspan, bdata.identification, bdata.singing, bdata.moving, bdata.habitat, 
            bdata.feeding, bdata.reproduction, bdata.population, bdata.conservation_threats as conservationThreats, 
            bdata.world_distribution as worldDistribution, bdata.peninsula_distribution as peninsulaDistribution, bdata.language as lang 
            FROM 
            bird_data bdata 
            WHERE bdata.bird_id = :id
        ";

        $sth = $this->db->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $bird_data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $bird_data;

    }

    /**
     * get family by order id
     * 
     * @param int $orderId
     * @return array $families
     * @throws \Throwable
     * 
     * */
    public function findByOrderId(int $orderId): array
    {
        try {
            $sth = $this->db->prepare('SELECT family.id, family.name, family.order_id as orderId, orders.name as orderName FROM family JOIN orders ON (family.order_id = orders.id) WHERE family.order_id = :orderId');
            $sth->bindParam(':orderId', $orderId);
            $sth->execute();
            $families = $sth->fetchAll(PDO::FETCH_ASSOC);
            if($families){
                return $families;
            } else {
                return ['NOT_FOUND'];
            }
        } catch (PDOException $e) {
            return ['FIND_FAIL'];
        }
    }

    /**
     * find images by bird id
     * 
     * @param int $birdId
     * @param int limit
     * @return array $images
     */
    public function findImagesByBirdId(int $birdId, int $limit = 0): array
    {
        if($limit > 0){
            $limit = "LIMIT $limit";
        } else {
            $limit = '';
        }
        $sth = $this->db->prepare("SELECT * FROM bird_img WHERE bird_id = :birdId $limit");
        $sth->bindParam(':birdId', $birdId);
        $sth->execute();
        $images = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $images;
    }

    /**
     * get frecuency of bird by id
     * 
     * @param int $birdId
     * @return array $frecuency
     */ 
    public function findFrecuencyByBirdId(int $birdId): array
    {   
        $sql = "SELECT
        frecuency.name as frecuencyName 
        FROM 
        frecuency 
        JOIN bird_frecuency ON (frecuency.id = bird_frecuency.frecuency_id) 
        WHERE bird_frecuency.bird_id = :birdId";

        $sth = $this->db->prepare($sql);
        $sth->bindParam(':birdId', $birdId);
        $sth->execute();
        $frecuency = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $frecuency;
    }

    /**
     * get bird month by id
     * 
     * @param int $birdId
     * @return array $months
     */
    public function findMonthsByBirdId(int $birdId): array
    {
        $sth = $this->db->prepare("SELECT p_month FROM bird_month WHERE bird_id = :birdId");
        $sth->bindParam(':birdId', $birdId);
        $sth->execute();
        $months = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $months;
    }
}