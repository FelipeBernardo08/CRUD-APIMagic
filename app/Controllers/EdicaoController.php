<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once './app/Models/Edicao.php';

class EditionController
{
    private $editionModel;

    public function __construct()
    {
        $this->editionModel = new EditionModel();
    }

    public function createEdition(Request $request, Response $response): object
    {
        $data = $request->getParsedBody();
        $result = $this->editionModel->createEdition($data);
        if ($result == 'ok') {
            $response->getBody()->write("Registro criado com sucesso!");
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser cadastrado!');
    }

    public function readEditions(Response $response): object
    {
        $editions = $this->editionModel->readEditions();
        if (count($editions) != 0) {
            $editionsJson = json_encode($editions);
            $response->getBody()->write($editionsJson);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registros não encontrados!');
    }

    public function readEditionId(Response $response, int $id): object
    {
        $edition = $this->editionModel->readEditionId($id);
        if (count($edition) != 0) {
            $editionJson = json_encode($edition);
            $response->getBody()->write($editionJson);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registros não encontrados!');
    }

    public function updateEditionUnit(Request $request, Response $response, int $id): object
    {
        $data = $request->getParsedBody();
        $colunas = array_keys($data);
        foreach ($colunas as $coluna) {
            $result =  $this->editionModel->updateEditionUnit($id, $coluna, $data);
        }
        if ($result == 'ok') {
            $response->getBody()->write('Resgistro atualizado com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser atualizado!');
    }

    public function updateEdition(Request $request, Response $response, int $id)
    {
        $data = $request->getParsedBody();
        $result =  $this->editionModel->updateEdition($id, $data);
        if ($result == 'ok') {
            $response->getBody()->write('Resgistro atualizado com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser atualizado!');
    }

    public function deleteEdition(Response $response, int $id): object
    {
        $result = $this->editionModel->deleteEdition($id);
        if ($result == 'ok') {
            $response->getBody()->write('Registro excluído com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser excluído!');
    }


    public function error(Response $response, string $msg): object
    {
        $response->getBody()->write($msg);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
