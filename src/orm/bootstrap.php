<?php

require_once BASE_PATH . 'src/orm/tables.php';

use function App\Orm\create_table_recovery_password;
use function App\Orm\create_table_user;
use function App\Orm\create_table_role;
use function App\Orm\create_table_user_roles;
use function App\Orm\create_table_documento;
use function App\Orm\create_table_tipo_documento;
use function App\Orm\create_table_carrera;
use function App\Orm\create_table_comentario;


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
create_table_recovery_password();
create_table_tipo_documento();
create_table_carrera();
create_table_documento();
create_table_comentario();


function create_initial_catalog_entry($table, $field, $value)
{
    $existingEntry = ORM::for_table($table)->where($field, $value)->find_one();
    if (!$existingEntry) {
        $entry = ORM::for_table($table)->create();
        $entry->$field = $value;
        $entry->save();
    } 
}


// Role Categories
create_initial_catalog_entry(table:'role',field:'role',value:'public');
create_initial_catalog_entry(table:'role',field:'role',value:'admin');

// Tipo Documento Categories
create_initial_catalog_entry(table: 'tipo_documento', field: 'tipo_documento', value: 'monografia');
create_initial_catalog_entry(table: 'tipo_documento', field: 'tipo_documento', value: 'ensayo');
create_initial_catalog_entry(table: 'tipo_documento', field: 'tipo_documento', value: 'trabajo de investigacion');
create_initial_catalog_entry(table: 'tipo_documento', field: 'tipo_documento', value: 'informe academico');
create_initial_catalog_entry(table: 'tipo_documento', field: 'tipo_documento', value: 'trabajo estadistico');
create_initial_catalog_entry(table: 'tipo_documento', field: 'tipo_documento', value: 'investigacion de accion');

