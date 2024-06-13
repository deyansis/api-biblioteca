<?php

require_once BASE_PATH . 'src/orm/tables.php';

use function App\Orm\create_table_user;
use function App\Orm\create_table_role;
use function App\Orm\create_table_user_roles;


$dbHost = $_ENV['DB_HOST'];
$dbUser = $_ENV['DB_USER'];
$dbPass = $_ENV['DB_PASS'];
$dbName = $_ENV['DB_NAME'];
$dbport = $_ENV['DB_PORT'];

ORM::configure("mysql:host=$dbHost:$dbport;dbname=$dbName");
ORM::configure('username', $dbUser);
ORM::configure('password', $dbPass);
ORM::configure('driver_options', [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);

// creando tablas
create_table_user();
create_table_role();
create_table_user_roles();


// datos iniciales
// Función para verificar y crear datos iniciales
function create_initial_role($roleName)
{
    $existingRole = ORM::for_table('role')->where('role', $roleName)->find_one();
    if (!$existingRole) {
        $role = ORM::for_table('role')->create();
        $role->role = $roleName;
        $role->save();
    } 
}

// Crear roles iniciales si no existen
create_initial_role('public');
create_initial_role('admin');
