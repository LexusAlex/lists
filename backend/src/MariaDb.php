<?php

namespace Lists;

use PDO;

class MariaDb
{
    public $connection;

    public function __construct($dsn, $user, $pass)
    {
        $this->connection = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function createTestTable()
    {
        $result = $this->connection->exec('CREATE TABLE IF NOT EXISTS `tree1` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `parent_id` INT NULL,
          `name` VARCHAR(255) NOT NULL,
          `description` VARCHAR(255) NOT NULL,
          `type` INT NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (parent_id) REFERENCES tree1 (id)
            ON UPDATE CASCADE
            ON DELETE CASCADE
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;');

        return $result;
    }

    public function createTestData()
    {
        $d = [
            [
                'name' => 'Корень',
                'description' => 'Корневой узел',
                'type' => 0
            ],
            [
                'parent_id' => 1,
                'name' => 'Блоги',
                'description' => 'Категория',
                'type' => 0
            ],
            [
                'parent_id' => 1,
                'name' => 'Программирование',
                'description' => 'Категория',
                'type' => 0
            ],
            [
                'parent_id' => 1,
                'name' => 'Сервисы',
                'description' => 'Категория',
                'type' => 0
            ],
            [
                'parent_id' => 3,
                'name' => 'Php',
                'description' => 'Категория php',
                'type' => 0
            ],
            [
                'parent_id' => 5,
                'name' => 'Руководство',
                'description' => 'Категория php руководство',
                'type' => 0
            ],
            [
                'parent_id' => 6,
                'name' => 'Руководство по phpunit',
                'description' => 'Руководство по phpunit',
                'type' => 1
            ],
            [
                'parent_id' => 4,
                'name' => 'Проверка орфорграфии',
                'description' => 'Проверка орфорграфии',
                'type' => 1
            ],
        ];

        foreach ($d as $value) {
            $query = 'INSERT INTO lists.tree1 (parent_id, name, description, type) VALUES(:parent_id, :name, :description, :type)';

            $params = [
                ':parent_id' => isset($value['parent_id']) ? $value['parent_id'] : null,
                ':name' => $value['name'],
                ':description' => $value['description'],
                ':type' => $value['type'],
            ];

            $stm = $this->connection->prepare($query);
            $stm->execute($params);
        }
    }

    public function all ()
    {
        $sql = 'SELECT * FROM tree1' ;
        return $this->connection->query($sql)->fetchAll();
    }
}
