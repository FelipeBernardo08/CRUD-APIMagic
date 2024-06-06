<?php
require_once './dbConnection.php';

class ImgCartamodel
{
    private $database;

    public function __construct()
    {
        $this->database = new DataBase();
    }

    public function createImg(array $data): string
    {
        $img = $data['img'];
        $id_carta = $data['id_carta'];

        try {
            $pdo = $this->database->getConnection();
            $stmt = $pdo->prepare(
                "insert into imagens_carta (img, id_carta) values (:img, :id_carta)"
            );
            $stmt->execute([
                'img' => $img,
                'id_carta' => $id_carta
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function readImg(int $id): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare(
            "select * from imagens_carta where id = :id"
        );
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteImg(int $id): string
    {
        try {
            $pdo = $this->database->getConnection();
            $stmt = $pdo->prepare(
                "delete from imagens_carta where id = :id"
            );
            $stmt->execute([
                'id' => $id
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
