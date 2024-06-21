<?php

declare(strict_types=1);

require_once 'src/utils/validator.php';
require_once 'src/models/user.model.php';
require_once 'src/services/user.services.php';

use App\Models\User;
use App\Services\User_Services;
use function App\Utils\validateRequiredFields;
use function App\Utils\validateRequiredFieldsFromClass;


Flight::route('POST /admin/create', function () {

    try {

        $request = Flight::request();
        $data = $request->data->getData();

        $validationResult = validateRequiredFieldsFromClass($data, User::class);

        if (!$validationResult['isValid']) {
            Flight::json([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $validationResult['missingFields'])
            ], 400);
            return;
        }

        $user = new User(
            nombre: $data['nombre'],
            apellido_materno: $data['apellido_materno'],
            apellido_paterno: $data['apellido_paterno'],
            email: $data['email'],
            password: $data['password']
        );

        $user_id = User_Services::create_user(user_data: $user, rol: 'admin');

        Flight::json([
            'status' => 'success',
            'message' => 'Usuario creado exitosamente.',
            'data' => ['user_id' => $user_id]
        ]);
    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


Flight::route('POST /admin/login', function () {

    try {
        $request = Flight::request();
        $data = $request->data->getData();

        $inputs_required = ['email', 'password'];
        $validationResult = validateRequiredFields($data, $inputs_required);

        if (!$validationResult['isValid']) {
            Flight::json([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $validationResult['missingFields'])
            ], 400);
            return;
        }

        $user = User_Services::login(email: $data['email'], password: $data['password'], rol: 'admin');


        Flight::json([
            'status' => 'success',
            'message' => 'Usuario autenticado exitosamente.',
            'data' => ['user' => $user]
        ]);
    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
        return;
    }
});
