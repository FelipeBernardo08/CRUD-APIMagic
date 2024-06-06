<?php

require_once './dbConnection.php';

class UserModel
{
    private $database;

    public function __construct()
    {
        $this->database = new DataBase();
    }

    public function login(string $email): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare(
            "select * from user where email = :email"
        );
        $stmt->execute([
            'email' => $email
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function createUser(array $data): string
    {
        $nome = $data['nome'];
        $email = $data['email'];
        $password = $data['password'];

        $pdo = $this->database->getConnection();

        $passwordEncrypted = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare(
                "insert into user (nome, email, password) values (:nome, :email, :password)"
            );
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'password' => $passwordEncrypted
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function readUsers(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query(
            "select * from user"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readUserId(int $id): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare(
            "select * from user where id = :id"
        );
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserUnit(int $id, string $coluna, array $data): string
    {
        try {
            $pdo = $this->database->getConnection();
            $allowed_columns = ['nome', 'email', 'password', 'token'];
            if (!in_array($coluna, $allowed_columns)) {
                throw new Exception("Coluna não permitida.");
            }
            if ($coluna == 'password') {
                $passwordEncrypted = password_hash($data[$coluna], PASSWORD_BCRYPT);
                $data[$coluna] = $passwordEncrypted;
            }
            $sql = "update user set $coluna = :value where id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'value' => $data[$coluna]
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function updateUser(int $id, array $data): string
    {
        $nome = $data['nome'];
        $email = $data['email'];
        $password = $data['password'];

        $passwordEncrypted = password_hash($password, PASSWORD_BCRYPT);

        try {
            $pdo = $this->database->getConnection();
            $stmt = $pdo->prepare(
                'update user set nome = :nome, email = :email, password = :password where id = :id'
            );
            $stmt->execute([
                'id' => $id,
                'nome' => $nome,
                'email' => $email,
                'password' => $passwordEncrypted
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteUser(int $id): string
    {
        $pdo = $this->database->getConnection();
        try {
            $stmt = $pdo->prepare(
                "delete from user where id = :id"
            );
            $stmt->execute([
                'id' => $id
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }


    public function getTokenClient(int $id): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare(
            "select token from user where id = :id"
        );
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
