<?php

declare (strict_types=1);

namespace App\Domain\News\Repository;

// get PDO
use PDO;

final class NewsCreateRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $user_id): int
    {
        $sql = "INSERT INTO news (user_id) VALUES (:user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->pdo->lastInsertId();
    }

    public function createNewsData(array $data): void
    {
        $sql = "INSERT INTO 
        news_data 
        (news_id, title, subtitle, body, lang) 
        VALUES 
        (:news_id, :title, :subtitle, :body, :lang)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $row) {
          $stmt->execute($row);
        }
    }

    /**
     * check if new exists
     * 
     * @param new_id
     * @return bool
     * @throws \Exception
     */
    public function checkNewsExists(int $new_id): bool
    {
        $sql = "SELECT * FROM news WHERE id = :new_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':new_id', $new_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * bind img and bird in bird_image table
     * 
     * @param img_name
     * @param new_id
     * @return void
     * @throws \Exception
     * 
     * */
    public function bindImage(int $new_id, string $img_name): void
    {
        $sql = "INSERT INTO news_img (news_id, img) VALUES (:new_id, :img)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':new_id', $new_id, PDO::PARAM_INT);
        $stmt->bindParam(':img', $img_name, PDO::PARAM_STR);
        $stmt->execute();
    }
}