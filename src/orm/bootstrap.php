<?php

require_once BASE_PATH . 'src/models/user/user.repository.php';

use App\Models\UserRepository as UserRepository;


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
UserRepository::create_table();
