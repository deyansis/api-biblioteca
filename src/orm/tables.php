<?php

declare(strict_types=1);

namespace App\Orm;

require_once BASE_PATH . 'src/utils/orm.php';

use function App\Utils\create_table_orm;
use function App\Utils\add_foreign_key;


function create_table_user(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'nombre' => 'VARCHAR(255)',
        'apellido_materno' => 'VARCHAR(255)',
        'apellido_paterno' => 'VARCHAR(255)',
        'email' => 'VARCHAR(255) UNIQUE',
        'password' => 'VARCHAR(255)',
    ];

    create_table_orm('user', $columns);
}

function create_table_role(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'role' => "VARCHAR(255)",
    ];

    create_table_orm('role', $columns);

}


function create_table_user_roles(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'role_id' => "INT",
        'user_id' => "INT"
    ];

    create_table_orm('user_role', $columns);
    add_foreign_key(table:"user_role",column:"role_id",foreignTable:"role",foreignColumn:"id");
    add_foreign_key(table:"user_role",column:"user_id",foreignTable:"user",foreignColumn:"id");
}