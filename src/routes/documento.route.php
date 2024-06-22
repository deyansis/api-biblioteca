<?php

declare(strict_types=1);


require_once 'src/services/documento.services.php';
require_once 'src/utils/validator.php';
require_once 'src/models/documento.model.php';


use App\Services\Documento_Services;
use function App\Utils\validateRequiredFieldsFromClass;
use function App\Utils\validateRequiredFields;
use function App\Utils\validateEstado;
use App\Models\Documento;



Flight::route('GET /documento/tipo_documento', function () {

    try {

        $tipo_documento = Documento_Services::get_all_tipos();

        Flight::json([
            'status' => 'success',
            'message' => 'Lista de Documentos Encontrado.',
            'data' => ['tipo_documento' => $tipo_documento]
        ]);
    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
        return;
    }
});


Flight::route('POST /documento/create', function () {

    try {

        $request = Flight::request();
        $data = $request->data->getData();

        $validationResult = validateRequiredFieldsFromClass($data, Documento::class);

        if (!$validationResult['isValid']) {
            Flight::json([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $validationResult['missingFields'])
            ], 400);
            return;
        }

        $documento = new Documento(
            autor: $data['autor'],
            nombre_archivo: $data['nombre_archivo'],
            year: $data['year'],
            archivo_base64: $data['archivo_base64'],
            estado: "revision",
            user_id: (int)$data['user_id'],
            carrera_id: (int)$data['carrera_id'],
            tipo_documento_id: (int)$data['tipo_documento_id']
        );

        $documento_id = Documento_Services::create_documento($documento);

        Flight::json([
            'status' => 'success',
            'message' => 'Documento creado exitosamente.',
            'data' => ['documento_id' => $documento_id]
        ]);

    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


Flight::route('PUT /documento', function () {

    try {

        $request = Flight::request();
        $data = $request->data->getData();

        $inputs_required = ['documento_id', 'estado'];

        $validationResult = validateRequiredFields($data, $inputs_required);

        if (!$validationResult['isValid']) {
            Flight::json([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $validationResult['missingFields'])
            ], 400);
            return;
        }

        if (!validateEstado($data['estado'])) {
            Flight::json([
                'status' => 'error',
                'message' => 'Valor de estado no válido. Los valores permitidos son: revision, rechazado, aprobado.'
            ], 400);
            return;
        }


        $documento_id = Documento_Services::update_estado(documento_id: (int)$data['documento_id'] , estado: $data['estado']);

        Flight::json([
            'status' => 'success',
            'message' => 'Documento actualizado exitosamente.',
            'data' => ['documento_id' => $documento_id]
        ]);
    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});