<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\FichaController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

$controller = new FichaController();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ($uri[1] !== 'fichas') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

switch ($requestMethod) {
    case 'GET':
        if (isset($uri[2])) {
            if ($uri[2] === 'search') {
                $controller->searchByName($_GET['name']);
            } elseif ($uri[2] === 'token') {
                $controller->searchByToken($_GET['token']);
            } else {
                $controller->show($uri[2]);
            }
        } else {
            $controller->index();
        }
        break;
    case 'POST':
        $controller->store();
        break;
    case 'PUT':
        if (isset($uri[2])) {
            $controller->update($uri[2]);
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;
    case 'DELETE':
        if (isset($uri[2])) {
            $controller->delete($uri[2]);
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;
    default:
        header("HTTP/1.1 405 Method Not Allowed");
        exit();
}
