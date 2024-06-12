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

    create_table_orm('users', $columns);
}

function create_table_user_roles(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'user_id' => 'INT',
        'role' => "ENUM('public', 'admin')",
    ];

    create_table_orm('user_roles', $columns);

    add_foreign_key('user_roles', 'user_id', 'users', 'id');
}
