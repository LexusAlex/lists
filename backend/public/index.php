<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

http_response_code(500);

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) {
    return $response;
});

$app->run();

echo "{}";



$tree = new \Lists\Tree();

$db = new \Lists\MariaDb('mysql:dbname=lists;host=backend-mariadb;port=3306', 'root', 'lists');
//$db->createTestTable();
//$db->createTestData();

print_r($tree->flatToTree2($db->all()));
print_r($db->all());
