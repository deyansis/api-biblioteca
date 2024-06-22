<?php

declare(strict_types=1);


require_once 'src/services/carrera.services.php';
require_once 'src/models/carrera.model.php';
require_once 'src/utils/validator.php';

use App\Services\Carrera_Services;
use function App\Utils\validateRequiredFieldsFromClass;
use App\Models\Carrera;

Flight::route('POST /carrera/create', function () {

    try {

        $request = Flight::request();
        $data = $request->data->getData();

        $validationResult = validateRequiredFieldsFromClass($data, Carrera::class);

        if (!$validationResult['isValid']) {
            Flight::json([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $validationResult['missingFields'])
            ], 400);
            return;
        }

        $carrera_id = Carrera_Services::create_carrera(carrera: $data['carrera']);

        Flight::json([
            'status' => 'success',
            'message' => 'Carrera creado exitosamente.',
            'data' => ['carrera_id' => $carrera_id]
        ]);

    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Flight::route('GET /carrera/all', function () {

    try {

        $carreras = Carrera_Services::get_all_carrera();

        Flight::json([
            'status' => 'success',
            'message' => 'Lista Encontrada.',
            'data' => ['carreras' => $carreras]
        ]);
     
    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
        return;
    }

});

