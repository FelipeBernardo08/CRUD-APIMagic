<?php
require_once 'vendor/autoload.php';
require_once './app/Controllers/AuthController.php';
require_once './app/Controllers/UserController.php';
require_once './app/Controllers/EdicaoController.php';
require_once './app/Controllers/CartaController.php';
require_once './app/Controllers/ImagemCartaController.php';

$app = new \Slim\App;
$userController = new UserController();
$authController = new AuthController();
$editionController = new EditionController();
$cartaController = new CartaController();
$imgCarta = new ImgCartaController();

$authMiddleware = function ($request, $response, $next) use ($authController) {
    $token = $request->getHeaderLine('Authorization');
    if (!$token) {
        return $response->withStatus(401)->withJson(['error' => 'Token isento!']);
    }

    if (!$authController->validateTokenClient($token)) {
        return $response->withStatus(401)->withJson(['error' => 'Token invalido!']);
    }

    $response = $next($request, $response);
    return $response;
};

//public routes : 

//auth
$app->post('/login', function ($request, $response) use ($authController) {
    return $authController->login($request, $response);
});

//carts
$app->get('/cards-read', function ($request, $response, $args) use ($cartaController) {
    return $cartaController->readCards($response);
});

$app->get('/card-read/{id}', function ($request, $response, $args) use ($cartaController) {
    return $cartaController->readCardId($response, $args['id']);
});

//protected routes
$app->group('', function () use ($app, $userController, $authController, $editionController, $cartaController, $imgCarta) {

    //user
    $app->post('/user-create', function ($request, $response, $args) use ($userController) {
        return $userController->createUser($request, $response);
    });

    $app->get('/users-read', function ($request, $response, $args) use ($userController) {
        return $userController->readUsers($response);
    });

    $app->get('/user-read/{id}', function ($request, $response, $args) use ($userController) {
        return $userController->readUserId($response, $args['id']);
    });

    $app->patch('/user-update-unit/{id}', function ($request, $response, $args) use ($userController) {
        return $userController->updateUserUnit($request, $response, $args['id']);
    });

    $app->put('/user-update/{id}', function ($request, $response, $args) use ($userController) {
        return $userController->updateUser($request, $response, $args['id']);
    });

    $app->delete('/user-delete/{id}', function ($request, $response, $args) use ($userController) {
        return $userController->deleteUser($response, $args['id']);
    });

    //auth
    $app->post('/logout/{id}', function ($request, $response, $args) use ($authController) {
        return $authController->logout($response, $args['id']);
    });

    //Edicao
    $app->get('/editions-read', function ($request, $response, $args) use ($editionController) {
        return $editionController->readEditions($response);
    });

    $app->get('/edition-read/{id}', function ($request, $response, $args) use ($editionController) {
        return $editionController->readEditionId($response, $args['id']);
    });

    $app->post('/edition-create', function ($request, $response, $args) use ($editionController) {
        return $editionController->createEdition($request, $response);
    });

    $app->patch('/edition-update-unit/{id}', function ($request, $response, $args) use ($editionController) {
        return $editionController->updateEditionUnit($request, $response, $args['id']);
    });

    $app->put('/edition-update/{id}', function ($request, $response, $args) use ($editionController) {
        return $editionController->updateEdition($request, $response, $args['id']);
    });

    $app->delete('/edition-delete/{id}', function ($request, $response, $args) use ($editionController) {
        return $editionController->deleteEdition($response, $args['id']);
    });

    //carta
    $app->post('/card-create', function ($request, $response) use ($cartaController) {
        return $cartaController->createCard($request, $response);
    });

    $app->patch('/card-update-unit/{id}', function ($request, $response, $args) use ($cartaController) {
        return $cartaController->updateCardUnit($request, $response, $args['id']);
    });

    $app->put('/card-update/{id}', function ($request, $response, $args) use ($cartaController) {
        return $cartaController->updateCard($request, $response, $args['id']);
    });

    $app->put('/card-delete/{id}', function ($request, $response, $args) use ($cartaController) {
        return $cartaController->deleteCard($response, $args['id']);
    });

    //img
    $app->post('/img-card-create', function ($request, $response, $args) use ($imgCarta) {
        return $imgCarta->createImg($request, $response);
    });

    $app->get('/img-read/{id}', function ($request, $response, $args) use ($imgCarta) {
        return $imgCarta->readImgId($response, $args['id']);
    });

    $app->delete('/img-delete/{id}',  function ($request, $response, $args) use ($imgCarta) {
        return $imgCarta->deleteImg($response, $args['id']);
    });
})->add($authMiddleware);

$app->run();
