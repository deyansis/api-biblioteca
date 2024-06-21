<?php

declare(strict_types=1);

require_once 'src/utils/validator.php';
require_once 'src/models/user.model.php';
require_once 'src/services/user.services.php';
require_once 'src/services/mail.services.php';


use App\Models\User;
use App\Services\User_Services;
use App\Services\Mail_Service;
use function App\Utils\validateRequiredFields;
use function App\Utils\validateRequiredFieldsFromClass;
use function App\Utils\validateEmailValid;


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
            nombre: $data['nombre'],
            apellido_materno: $data['apellido_materno'],
            apellido_paterno: $data['apellido_paterno'],
            email: $data['email'],
            password: $data['password']
        );

        $user_id = User_Services::create_user(user_data: $user, rol: 'public');

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



Flight::route('GET /user/@id', function (string $id) {

    try {



        $user = User_Services::find_by_id($id);

        Flight::json([
            'status' => 'success',
            'message' => 'Usuario encontrado.',
            'data' => ['user' => $user]
        ]);

    } catch (Exception $e) {


        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});



Flight::route('POST /user/email', function () {

    try {

        $request = Flight::request();
        $data = $request->data->getData();

        $email = $data["email"];



        if (!validateEmailValid($email)) {

            Flight::json([
                'status' => 'error',
                'message' => 'Email Invalido'
            ], 400);
            return;
        }

        $user = User_Services::find_by_email($email);

        Flight::json([
            'status' => 'success',
            'message' => 'Usuario encontrado.',
            'data' => ['user' => $user]
        ]);

    } catch (Exception $e) {


        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});



Flight::route('POST /user/login', function () {

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

        $user = User_Services::login(email: $data['email'], password: $data['password'], rol: 'public');


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


Flight::route('POST /user/seend/resetpassword', function () {
    try {
        $request = Flight::request();
        $data = $request->data->getData();

        $email = $data["email"];



        if (!validateEmailValid($email)) {

            Flight::json([
                'status' => 'error',
                'message' => 'Email Invalido'
            ], 400);
            return;
        }


        $randomNumber = rand(100000, 999999);
        $randomString = strval($randomNumber);


        $code_recovery = User_Services::generate_code_recovery(code:$randomString, email:$email);

        $to = $email;
        $subject = 'Codigo de cambio de contraseña';
        $body = "<h1>Código {$randomNumber}</h1>";

        $mailService = new Mail_Service();
        $mailService->sendMail(to: $to, subject: $subject, body: $body);

        Flight::json([
            'status' => 'success',
            'message' => 'Correo enviado correctamente.',
            'data' => ['code' => $code_recovery]
        ]);

    } catch (Exception $e) {
        Flight::json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
        return;
    }
});




Flight::route('POST /user/password', function () {
    try {
        $request = Flight::request();
        $data = $request->data->getData();

        $newPassword = $data['newPassword'];
        $id_recovery = $data['idRecovery'];


        $id = User_Services::reset_password(newpassword: $newPassword, idRecovery: $id_recovery);

        Flight::json([
            'status' => 'success',
            'message' => 'Password restablecido correctamente.',
            'data' => ['user_id' => $id]
        ]);

    } catch (Exception $e) {
    }
});