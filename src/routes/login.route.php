<?php

declare(strict_types=1);

require_once 'src/utils/validator.php';
require_once 'src/models/user/user.repository.php';

use function App\Utils\validateRequiredFields;
use App\Models\UserRepository;
use App\Utils\PasswordUtil;

Flight::route('POST /login', function () {

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

        $user = UserRepository::find_by_email($data['email']);
        $is_password = PasswordUtil::verifyPassword($data['password'], $user->password);


        if ($user && $is_password) {
            Flight::json([
                'status' => 'success',
                'message' => 'Usuario autenticado exitosamente.',
                'data' => ['user' => $user->user_without_password()]
            ]);
            return;
        } else {
            Flight::json([
                'status' => 'error',
                'message' => 'Usuario o contraseña incorrectos.'
            ], 400);
            return;
        }
    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
        return;
    }
});
