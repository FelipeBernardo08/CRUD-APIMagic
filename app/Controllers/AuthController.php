<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;
use \Firebase\JWT\Key;

require_once './app/Models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(Request $request, Response $response): object
    {
        $data = $request->getParsedBody();
        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userModel->login($email, $password);

        if (count($user) > 0) {
            if (password_verify($password, $user['password'])) {
                $token = $this->generateJWT($this->generateSecret(), $user['id']);
                $dataToken = ["token" => $token];
                $this->userModel->updateUserUnit($user['id'], 'token', $dataToken);
                $response->getBody()->write(json_encode(['token' => $token]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
            $response->getBody()->write('Credenciais incorretas!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        } else {
            $response->getBody()->write('Registro não encontrado!');
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    public function logout(Response $response, int $id): object
    {
        $data = ['token' => null];
        $this->userModel->updateUserUnit($id, 'token', $data);
        $response->getBody()->write('Usuário deslogado!');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function validateTokenClient(string $token)
    {
        $onlyToken = str_replace('Bearer ', '', $token);
        $decoded = JWT::decode($onlyToken, new Key($this->generateSecret(), 'HS256'));
        $id = $decoded->userId;
        $tokenClient = $this->userModel->getTokenClient($id);
        if (count($tokenClient) != 0) {
            if ($tokenClient['token'] == $onlyToken) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function generateSecret(): string
    {
        $dotenv = Dotenv::createImmutable('././');
        $dotenv->load();
        return $_ENV['SECRET_KEY'];
    }

    public function generateJWT(string $secret, string $userId): string
    {
        $payload = [
            'userId' => $userId,
            'exp' => time() + 3600
        ];
        $token = JWT::encode($payload, $secret, 'HS256');
        return $token;
    }
}
