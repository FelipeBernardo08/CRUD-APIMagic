<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once './app/Models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function createUser(Request $request, Response $response): object
    {
        $data = $request->getParsedBody();
        $result = $this->userModel->createUser($data);
        if ($result == 'ok') {
            $response->getBody()->write("Registro criado com sucesso!");
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser criado!');
    }

    public function readUsers(Response $response): object
    {
        $users = $this->userModel->readUsers();
        if (count($users) != 0) {
            $usersJson = json_encode($users);
            $response->getBody()->write($usersJson);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registros não encontrados!');
    }

    public function readUserId(Response $response, int $id): object
    {
        $user = $this->userModel->readUserId($id);
        if (count($user) != 0) {
            $userJson = json_encode($user);
            $response->getBody()->write($userJson);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não encontrado!');
    }

    public function updateUserUnit(Request $request, Response $response, int $id): object
    {
        $data = $request->getParsedBody();
        $colunas = array_keys($data);
        foreach ($colunas as $coluna) {
            $result =  $this->userModel->updateUserUnit($id, $coluna, $data);
        }
        if ($result == 'ok') {
            $response->getBody()->write('Resgistro atualizado com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser atualizado!');
    }

    public function updateUser(Request $request, Response $response, int $id): object
    {
        $data = $request->getParsedBody();
        $result =  $this->userModel->updateUser($id, $data);
        if ($result == 'ok') {
            $response->getBody()->write('Resgistro atualizado com sucesso!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        return $this->error($response, 'Registro não pode ser atualizado!');
    }

    public function deleteUser(Response $response, int $id): object
    {
        $result = $this->userModel->deleteUser($id);
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
