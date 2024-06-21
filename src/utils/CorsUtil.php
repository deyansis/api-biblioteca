<?php

declare(strict_types=1);
namespace App\Utils;

use Flight;

class CorsUtil
{
    public function set(array $params): void
    {
        $solicitud = Flight::request();
        $respuesta = Flight::response();
        if ($solicitud->getVar('HTTP_ORIGIN') !== '') {
            $this->permitirOrígenes();
            $respuesta->header('Access-Control-Allow-Credentials', 'true');
            $respuesta->header('Access-Control-Max-Age', '86400');
        }

        if ($solicitud->method === 'OPTIONS') {
            if ($solicitud->getVar('HTTP_ACCESS_CONTROL_REQUEST_METHOD') !== '') {
                $respuesta->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD');
            }
            if ($solicitud->getVar('HTTP_ACCESS_CONTROL_REQUEST_HEADERS') !== '') {
                $respuesta->header('Access-Control-Allow-Headers', $solicitud->getVar('HTTP_ACCESS_CONTROL_REQUEST_HEADERS'));
            }

            $respuesta->status(200);
            $respuesta->send();
            exit;
        }
    }

    private function permitirOrígenes(): void
    {
        // personaliza tus hosts permitidos aquí.
        $permitidos = [
            'capacitor://localhost',
            'ionic://localhost',
            'http://localhost',
            'http://localhost:4322',
        ];

        $solicitud = Flight::request();

        if (in_array($solicitud->getVar('HTTP_ORIGIN'), $permitidos, true) === true) {
            $respuesta = Flight::response();
            $respuesta->header("Access-Control-Allow-Origin", $solicitud->getVar('HTTP_ORIGIN'));
        }
    }
}