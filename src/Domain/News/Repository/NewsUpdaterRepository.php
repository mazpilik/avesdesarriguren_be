<?php

namespace App\Domain\News\Repository;

use PDO;

final class NewsUpdaterRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * update news
     * 
     * @param int $id
     * @param string $updated_at
     * @return void
     */
    public function updateNews(int $id, string $updated_at): void
    {
        $sql = "UPDATE news SET updated_at = :updated_at WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->execute();
    }

    /**
     * update news data
     * 
     * @param int $id
     * @param array $additional_data_array
     * @return void
     * @throws \Exception
     * 
     */
    public function updateAdditionalData(int $id, iterable $additional_data_array): void
    {
      $sql = "UPDATE news_data 
      SET 
      title = :title, 
      subtitle = :subtitle, 
      body = :body 
      WHERE news_id = :news_id AND lang = :lang";
      $stmt = $this->db->prepare($sql);
      
      foreach ($additional_data_array as $row) {
        $stmt->execute($row);
      }
    }

    /**
     * delete images by news_id and img
     * 
     * @param array $images
     * @return bool $result
     */
    public function deleteImage(int $id)
    {
        $sth = $this->db->prepare("DELETE FROM news_img WHERE news_id = :news_id");
        $sth->bindParam(':news_id', $id);
        $sth->execute();
    }
}