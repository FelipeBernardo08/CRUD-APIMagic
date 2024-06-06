<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once './app/Models/Carta.php';

class CartaController
{
    private $cartaModel;

    public function __construct()
    {
        $this->cartaModel = new CartaModel();
    }

    public function createCard(Request $request, Response $response): object
    {
        $data = $request->getParsedBody();
        $result = $this->cartaModel->createCard($data);
        if ($result == 'ok') {
            $response->getBody()->write("Registro criado com sucesso!");
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser cadastrado!');
    }

    public function readCards(Response $response): object
    {
        $carts = $this->cartaModel->readCards();
        if (count($carts) != 0) {
            $cartsJson = json_encode($carts);
            $response->getBody()->write($cartsJson);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registros não encontrados!');
    }

    public function readCardId(Response $response, int $id)
    {
        $cart = $this->cartaModel->readCardId($id);
        if (count($cart) != 0) {
            $cartJson = json_encode($cart);
            $response->getBody()->write($cartJson);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registros não encontrados!');
    }

    public function updateCardUnit(Request $request, Response $response, int $id): object
    {
        $data = $request->getParsedBody();
        $colunas = array_keys($data);
        foreach ($colunas as $coluna) {
            $result =  $this->cartaModel->updateCardUnit($data, $coluna, $id);
        }
        if ($result == 'ok') {
            $response->getBody()->write('Resgistro atualizado com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser atualizado!');
    }

    public function updateCard(Request $request, Response $response, int $id): object
    {
        $data = $request->getParsedBody();
        $result =  $this->cartaModel->updateCard($id, $data);
        if ($result == 'ok') {
            $response->getBody()->write('Resgistro atualizado com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser atualizado!');
    }

    public function deleteCard(Response $response, int $id): object
    {
        $result = $this->cartaModel->deleteCard($id);
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
