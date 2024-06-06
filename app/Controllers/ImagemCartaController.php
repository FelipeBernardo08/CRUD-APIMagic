<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once './app/Models/ImagemCarta.php';

class ImgCartaController
{
    private $imgCarta;

    public function __construct()
    {
        $this->imgCarta = new ImgCartamodel();
    }

    public function createImg(Request $request, Response $response): object
    {
        $directory = __DIR__ . '../../Images';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['img'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedFile);
            $data = $request->getParsedBody();
            $data['img'] = $filename;
            $result = $this->imgCarta->createImg($data);
            if ($result == 'ok') {
                $response->getBody()->write('Registro criado com sucesso!');
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
        }
        return $this->error($response, 'Registro não pode ser criado!');
    }

    public function moveUploadedFile(string $directory, \Psr\Http\Message\UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public function readImgId(Response $response, int $id)
    {
        $img = $this->imgCarta->readImg($id);
        $directory = __DIR__ . '../../Images';
        $filename = $img['img'];
        $filePath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filePath)) {
            $this->error($response, 'Arquivo não encontrado');
        }

        $image = file_get_contents($filePath);
        $response->getBody()->write($image);

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $response = $response->withHeader('Content-Type', 'image/jpeg');
                break;
            case 'png':
                $response = $response->withHeader('Content-Type', 'image/png');
                break;
            case 'gif':
                $response = $response->withHeader('Content-Type', 'image/gif');
                break;
            default:
                $response = $response->withHeader('Content-Type', 'application/octet-stream');
        }

        return $response;
    }

    public function deleteImg(Response $response, int $id): object
    {
        $img = $this->imgCarta->readImg($id);
        $directory = __DIR__ . '../../Images';
        $filename = $img['img'];
        $filePath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                $result = $this->imgCarta->deleteImg($id);
                if ($result == 'ok') {
                    $response->getBody()->write('Registro deletado com sucesso!');
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
                }
            }
        }

        return $this->error($response, 'Registro não pode ser deletado!');
    }

    public function error(Response $response, string $msg): object
    {
        $response->getBody()->write($msg);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
