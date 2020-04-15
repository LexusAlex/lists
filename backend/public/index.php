<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

http_response_code(500);

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello");
    return $response;
});

$app->run();

echo "{}";


$d = [
    [
        'id' => 1,
        'parent_id' => 0,
        'name' => '/',
        'type' => 0
    ],
    [
        'id' => 2,
        'parent_id' => 1,
        'name' => 'etc',
        'type' => 0
    ],
    [
        'id' => 5,
        'parent_id' => 2,
        'name' => 'dir',
        'type' => 0
    ],
    [
        'id' => 10,
        'parent_id' => 5,
        'name' => 'subfile',
        'type' => 1
    ],
    [
        'id' => 3,
        'parent_id' => 2,
        'name' => 'file',
        'type' => 1
    ],
    [
        'id' => 4,
        'parent_id' => 1,
        'name' => 'usr',
        'type' => 0
    ],
];

$tree = new \Lists\Lists();
$t = $tree->flatToTree($d, 0);

echo '<pre>';
print_r($t);

/*$connection = new PDO('mysql:dbname=lists;host=backend-mariadb;port=3306', 'root', 'lists', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);*/
