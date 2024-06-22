<?php

declare(strict_types=1);

require_once 'src/services/comentario.services.php';
require_once 'src/models/comentario.model.php';
require_once 'src/utils/validator.php';


use App\Services\Comentario_Services;
use function App\Utils\validateRequiredFieldsFromClass;
use App\Models\Comentario;




Flight::route('POST /comentario/create', function () {

    try {

        $request = Flight::request();
        $data = $request->data->getData();

        $validationResult = validateRequiredFieldsFromClass($data, Comentario::class);

        if (!$validationResult['isValid']) {
            Flight::json([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $validationResult['missingFields'])
            ], 400);
            return;
        }

        $comentario = new Comentario(
            comentario: $data['comentario'],
            documento_id: (int)$data['documento_id'],
            user_id: (int)$data['user_id']
        );

        $comentario_id = Comentario_Services::create_comentario(comentario_data: $comentario);

        Flight::json([
            'status' => 'success',
            'message' => 'Comentario creado exitosamente.',
            'data' => ['comentario_id' => $comentario_id]
        ]);

    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});