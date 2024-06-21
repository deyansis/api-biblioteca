<?php

declare(strict_types=1);

namespace App\Services;

require_once 'src/utils/password_bcrypt.php';
require_once 'src/models/user.model.php';


use App\Utils\PasswordUtil;
use App\Models\User;
use ORM;
use PDOException;
use Exception;

class User_Services
{
    public static function create_user(User $user_data, string $rol): string
    {
        try {

            $encrypted_password = PasswordUtil::encryptPassword($user_data->password);

            ORM::get_db()->beginTransaction();
            $user = ORM::for_table('user')->create();
            $user->nombre = $user_data->nombre;
            $user->apellido_materno = $user_data->apellido_materno;
            $user->apellido_paterno = $user_data->apellido_paterno;
            $user->email = $user_data->email;
            $user->password = $encrypted_password;
            $user->save();

            $role = ORM::for_table('role')->where('role', $rol)->find_one();

            if (!$role) {
                ORM::get_db()->rollBack();
                throw new Exception("No se encontro el rol");
            }

            $user_role = ORM::for_table('user_role')->create();
            $user_role->user_id = $user->id;
            $user_role->role_id = $role->id;
            $user_role->save();

            ORM::get_db()->commit();

            return $user->id;

        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }


    public static function find_by_id(string $id)
    {
        try {
            $user = ORM::for_table('user')->find_one($id);

            if (!$user) {
                throw new Exception("Usuario no encontrado");
            }

            $user_result = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido_materno' => $user->apellido_materno,
                'apellido_paterno' => $user->apellido_paterno,
                'email' => $user->email
            ];


            return $user_result;
        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }


    public static function find_by_email(string $email)
    {
        try {
            $user = ORM::for_table('user')->where('email', $email)->find_one();

            if (!$user) {
                throw new Exception("Usuario no encontrado");
            }

            $user_result = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido_materno' => $user->apellido_materno,
                'apellido_paterno' => $user->apellido_paterno,
                'email' => $user->email
            ];


            return $user_result;
        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }


    public static function login(string $email, string $password, string $rol)
    {
        try {
            $user = ORM::for_table('user')->where('email', $email)->find_one();

            if (!$user) {
                throw new Exception("No se encontró el usuario o la contraseña es incorrecta");
            }

            $is_password = PasswordUtil::verifyPassword($password, $user->password);

            if (!$is_password) {
                throw new Exception("No se encontró el usuario o la contraseña es incorrecta");
            }

            $user_role = ORM::for_table('user_role')
                ->select('role.role')
                ->join('role', ['user_role.role_id', '=', 'role.id'])
                ->where('user_role.user_id', $user->id)
                ->find_one();

            if (!$user_role || $user_role->role !== $rol) {
                throw new Exception("No tienes permisos para ingresar");
            }

            $user_result = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido_materno' => $user->apellido_materno,
                'apellido_paterno' => $user->apellido_paterno,
                'email' => $user->email
            ];

            return $user_result;
        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }


    public static function reset_password(string $newpassword, string $idRecovery)
    {
        try {
            


            $encrypted_password = PasswordUtil::encryptPassword($newpassword);

            $code = ORM::for_table('recovery_password')->find_one(intval($idRecovery));

            if (!$code) {
                throw new Exception("Usuario no encontrado");
            }

            $user = ORM::for_table('user')->where('email', $code->email)->find_one();
            $user->set('password', $encrypted_password);
            $user->save();

            return $user->id;

        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }



    public static function generate_code_recovery(string $code, string $email)
    {

        try {


            $code_recovery = ORM::for_table('recovery_password')->create();
            $code_recovery->email = $email;
            $code_recovery->code = $code;
            $code_recovery->save();

            return $code_recovery->id;
        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }


    }

}


