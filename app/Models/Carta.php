<?php
require_once './dbConnection.php';

class CartaModel
{
    private $database;

    public function __construct()
    {
        $this->database = new DataBase();
    }

    public function createCard(array $data): string
    {
        $pdo = $this->database->getConnection();

        $id_edicao = $data['id_edicao'];
        $nomePtBr = $data['nomePtBr'];
        $nomeIng = $data['nomeIng'];
        $cor = $data['cor'];
        $artista = $data['artista'];
        $raridade = $data['raridade'];
        $descricao = $data['descricao'];
        $preco = $data['preco'];
        $qtd_estoque = $data['qtd_estoque'];

        try {
            $stmt = $pdo->prepare(
                "insert into carta (id_edicao, nomePtBr, nomeIng, cor, artista, raridade,  descricao, preco, qtd_estoque) 
                values (:id_edicao, :nomePtBr, :nomeIng, :cor, :artista, :raridade,  :descricao, :preco, :qtd_estoque)"
            );

            $stmt->execute([
                "id_edicao" => $id_edicao,
                "nomePtBr" => $nomePtBr,
                "nomeIng" => $nomeIng,
                "cor" => $cor,
                "artista" => $artista,
                "raridade" => $raridade,
                "descricao" => $descricao,
                "preco" => $preco,
                "qtd_estoque" => $qtd_estoque,
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function readCards(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('
            select carta.*, edicao.*, imagens_carta.* 
            from carta 
            inner join edicao on carta.id_edicao = edicao.id 
            inner join imagens_carta on carta.id = imagens_carta.id_carta
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readCardId(int $id): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare('
            select carta.*, edicao.*, imagens_carta.*
            from carta
            inner join edicao on carta.id_edicao = edicao.id
            inner join imagens_carta on carta.id = imagens_carta.id_carta
            where carta.id = :id
        ');
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCardUnit(array $data, string $coluna, int $id): string
    {
        try {
            $pdo = $this->database->getConnection();
            $allowed_columns = ['id_edicao', 'nomePtBr', 'nomeIng', 'cor', 'artista', 'raridade', 'id_imagem', 'descricao', 'preco', 'qtd_estoque'];
            if (!in_array($coluna, $allowed_columns)) {
                throw new Exception("Coluna não permitida.");
            }
            $sql = "update carta set $coluna = :value where id = :id";
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
    public function updateCard(int $id, array $data): string
    {
        $id_edicao = $data['id_edicao'];
        $nomePtBr = $data['nomePtBr'];
        $nomeIng = $data['nomeIng'];
        $cor = $data['cor'];
        $artista = $data['artista'];
        $raridade = $data['raridade'];
        $descricao = $data['descricao'];
        $preco = $data['preco'];
        $qtd_estoque = $data['qtd_estoque'];

        try {
            $pdo = $this->database->getConnection();
            $stmt = $pdo->prepare(
                "update carta set id_edicao = :id_edicao, nomePtBr = :nomePtBr , nomeIng = :nomeIng, cor = :cor , 
                artista = :artista, raridade = :raridade, descricao = :descricao, preco = :preco, qtd_estoque = :qtd_estoque where id = :id"
            );
            $stmt->execute([
                'id' => $id,
                'id_edicao' => $id_edicao,
                'nomePtBr' => $nomePtBr,
                'nomeIng' => $nomeIng,
                'cor' => $cor,
                'artista' => $artista,
                'raridade' => $raridade,
                'descricao' => $descricao,
                'preco' => $preco,
                'qtd_estoque' => $qtd_estoque,
            ]);
            return 'ok';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteCard(int $id): string
    {
        $pdo = $this->database->getConnection();
        try {
            $stmt = $pdo->prepare(
                "delete from carta where id = :id"
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
