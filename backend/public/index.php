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
echo '<pre>';

/*
$new = array();
foreach ($db->all() as $a){
    $new[$a['parent_id']][] = $a;
}
$tree = $tree->createTree($new, $new[1]); // changed
print_r($tree);
*/

//print_r($tree->build_tree($db->all(),'parent_id', 'id'));

print_r($tree->reduce(function ($acc, $n){ return $n;}, $tree->build_tree($db->all(),'parent_id', 'id'),[]));

print_r($db->all());
