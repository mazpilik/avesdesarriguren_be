<?php

namespace App\Domain\News\Repository;

use PDO;

final class NewsFinderRepository
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
        $sth = $this->db->prepare('SELECT * FROM news');
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
        $lang = $data['lang'];

        $where_condition = "WHERE lang = '$lang'";
        $where_value = '';
        $offset = ($page - 1) * $limit;

        if(!empty($where)) {
            $where_value = '"%'.$where.'%"';
            $where_condition = $where_condition." AND name LIKE $where_value";
        }

        $sql  = "SELECT 
        ndata.news_id as id, ndata.title, ndata.subtitle, ndata.body, 
        news.created_at as createdAt, 
        news.updated_at as updatedAt,
        news_img.img 
        FROM news_data ndata 
        JOIN news ON (news.id = ndata.news_id) 
        JOIN news_img ON (news.id = news_img.news_id) 
        $where_condition 
        ORDER BY $orderby $direction 
        LIMIT $limit OFFSET $offset";

        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        $news = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $news;
    }

    /**
     * get news count
     */
    public function getNewsCount(string $lang, string $where): int
    {
        $where_condition = ' WHERE lang = :lang';
        if(!empty($where)){
          $where_value = "%$where%";
          $where_condition = ' AND name LIKE :name';
        }

        $sth = $this->db->prepare('SELECT COUNT(*) FROM news_data'.$where_condition);
        if(!empty($where)){
            $sth->bindParam(':name', $where_value, PDO::PARAM_STR);
        }
        $sth->bindParam(':lang', $lang, PDO::PARAM_STR);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    /**
     * get news basic info by id
     * 
     * @param int $id
     * @return array $news
     */
    public function findNewsByid(int $id): array
    {
        $sql = "SELECT 
        news.id,
        news.created_at as createdAt,
        news.updated_at as updatedAt,
        nf.img 
        FROM news
        JOIN news_img nf ON (news.id = nf.news_id)
        WHERE news.id = :id";

        $sth = $this->db->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $news = $sth->fetch(PDO::FETCH_ASSOC);
        return $news;
    }

    /**
     * get news data by id
     * 
     * @param int $id
     * @return array $news_data
     */
    public function findDataByid(int $id): array
    {
        $sql = "SELECT 
            ndata.title, 
            ndata.subtitle, 
            ndata.body, 
            ndata.lang 
            FROM 
            news_data ndata 
            WHERE ndata.news_id = :id
        ";

        $sth = $this->db->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $news_data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $news_data;

    }

    /**
     * get news img by id
     * 
     * @param int $id
     * @return array $news_img
     */
    public function findImagesByNewsId(int $id): array
    {
        $sql = "SELECT 
            nimg.img 
            FROM 
            news_img nimg 
            WHERE nimg.news_id = :id
        ";

        $sth = $this->db->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $news_img = $sth->fetch(PDO::FETCH_ASSOC);
        if(is_array($news_img)){
            return $news_img;
        }
        return [];
        
    }

}