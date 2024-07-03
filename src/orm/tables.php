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


function create_table_recovery_password(): void
{

    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'email' => "VARCHAR(255)",
        'code' => "VARCHAR(6)"
    ];

    create_table_orm('recovery_password', $columns);
}

function create_table_tipo_documento(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'tipo_documento' => "VARCHAR(120) UNIQUE",
    ];

    create_table_orm('tipo_documento', $columns);
}



function create_table_carrera(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'carrera' => "VARCHAR(120) UNIQUE",
    ];

    create_table_orm('carrera', $columns);
}


function create_table_documento(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'autor' => "VARCHAR(120)",
        'nombre_archivo' => "VARCHAR(255)",
        'year' => "DATETIME",
        'estado' => "VARCHAR(120)",
        'archivo_base64' => "MEDIUMTEXT",
        'user_id' => 'INT ',
        'carrera_id' => 'INT',
        'tipo_documento_id' => 'INT ',
    ];

    create_table_orm('documento', $columns);
    add_foreign_key(table:"documento",column:"user_id",foreignTable:"user",foreignColumn:"id");
    add_foreign_key(table:"documento",column:"tipo_documento_id",foreignTable:"tipo_documento",foreignColumn:"id");
    add_foreign_key(table:"documento",column:"carrera_id",foreignTable:"carrera",foreignColumn:"id");
}

function create_table_comentario(): void
{
    $columns = [
        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
        'comentario' => "VARCHAR(255)",
        'nombre_usuario' => "VARCHAR(255)",
    ];

    create_table_orm('comentario', $columns);
}

