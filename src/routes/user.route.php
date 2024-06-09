<?php

declare(strict_types=1);

require_once 'src/utils/validator.php';
require_once 'src/models/user/user.model.php';
require_once 'src/models/user/user.repository.php';

use App\Models\User;
use App\Models\UserRepository;
use function App\Utils\validateRequiredFieldsFromClass;


Flight::route('POST /user/create', function () {

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
            $data['nombre'],
            $data['apellido_materno'],
            $data['apellido_paterno'],
            $data['email'],
            $data['password']
        );

        $user_id = UserRepository::create($user);

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
        return;
    }
});



Flight::route('GET /user/@id', function (string $id) {

    try {
        $user = UserRepository::find_by_id($id);
        if ($user) {

            Flight::json([
                'status' => 'success',
                'message' => 'Usuario encontrado.',
                'data' => ['user' => $user->user_without_password()]
            ]);
        } else {

            Flight::json([
                'status' => 'error',
                'message' => 'Usuario no encontrado.'
            ], 404);
        }
    } catch (Exception $e) {


        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
