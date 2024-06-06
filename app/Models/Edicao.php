<?php

require_once './dbConnection.php';

class EditionModel
{
    private $database;

    public function __construct()
    {
        $this->database = new DataBase();
    }

    public function createEdition(array $data): string
    {
        $pdo = $this->database->getConnection();

        $nomePtBr = $data['nomePtBr'];
        $nomeIng = $data['nomeIng'];
        $data_lancamento = $data['data_lancamento'];
        $quantidade_lancamento = $data['quantidade_lancamento'];

        try {
            $stmt = $pdo->prepare(
                "insert into edicao (nomePtBr, nomeIng, data_lancamento, quantidade_lancamento) 
                values (:nomePtBr, :nomeIng, :data_lancamento, :quantidade_lancamento)"
            );

            $stmt->execute([
                "nomePtBr" => $nomePtBr,
                "nomeIng" => $nomeIng,
                "data_lancamento" => $data_lancamento,
                "quantidade_lancamento" => $quantidade_lancamento
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function readEditions(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query(
            'select * from edicao'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function readEditionId(int $id): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare(
            'select * from edicao where id = :id'
        );
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEditionUnit(int $id, string $coluna, array $data): string
    {
        try {
            $pdo = $this->database->getConnection();
            $allowed_columns = ['nomePtBr', 'nomeIng', 'data_lancamento', 'quantidade_lancamento'];
            if (!in_array($coluna, $allowed_columns)) {
                throw new Exception("Coluna não permitida.");
            }
            $sql = "update edicao set $coluna = :value where id = :id";
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

    public function updateEdition(int $id, array $data): string
    {
        $nomePtBr = $data['nomePtBr'];
        $nomeIng = $data['nomeIng'];
        $data_lancamento = $data['data_lancamento'];
        $quantidade_lancamento = $data['quantidade_lancamento'];
        try {
            $pdo = $this->database->getConnection();
            $stmt = $pdo->prepare(
                'update edicao set nomePtBr = :nomePtBr, nomeIng = :nomeIng, 
                data_lancamento = :data_lancamento, quantidade_lancamento = :quantidade_lancamento where id = :id'
            );
            $stmt->execute([
                'id' => $id,
                'nomePtBr' => $nomePtBr,
                'nomeIng' => $nomeIng,
                'data_lancamento' => $data_lancamento,
                'quantidade_lancamento' => $quantidade_lancamento
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteEdition(int $id): string
    {
        $pdo = $this->database->getConnection();
        try {
            $stmt = $pdo->prepare(
                "delete from edicao where id = :id"
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
