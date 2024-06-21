<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__ . '/');
require_once 'src/utils/CorsUtil.php';

use App\Utils\CorsUtil;

require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'config.php';

// ORM
require_once BASE_PATH . 'src/orm/bootstrap.php';

// Routes
require_once BASE_PATH . 'src/routes/user.route.php';
require_once BASE_PATH . 'src/routes/admin.route.php';



// Pagina de inicio
Flight::route('/', function () {
    require_once BASE_PATH . 'src/pages/home.page.php';
});


$CorsUtil = new CorsUtil();
Flight::before('start', [$CorsUtil, 'set']);

// Iniciar la aplicación FlightPHP
Flight::start();
