<?php

declare(strict_types=1);

namespace App\Models;


require_once BASE_PATH . 'src/utils/password_bcrypt.php';




interface IUserRepository
{
    public static function create(User $user): string;
    public static function find_by_id(string $id): ?User;
    public static function find_by_email(string $email): ?User;
}



use App\Utils\PasswordUtil;
use ORM;
use PDOException;
use PDO;
use Exception;

class UserRepository  implements IUserRepository
{

    public static function create(User $user): string
    {

        try {

            ORM::get_db()->beginTransaction();

            $encrypted_password = PasswordUtil::encryptPassword($user->password);

            $userModel = ORM::for_table('users')->create();
            $userModel->nombre = $user->nombre;
            $userModel->apellido_materno = $user->apellido_materno;
            $userModel->apellido_paterno = $user->apellido_paterno;
            $userModel->email = $user->email;
            $userModel->password = $encrypted_password;
            $userModel->save();

            $lastUserId = ORM::get_db()->lastInsertId();

            $roleModel = ORM::for_table('user_roles')->create();
            $roleModel->user_id = $lastUserId;
            $roleModel->role = 'public';
            $roleModel->save();

            ORM::get_db()->commit();

            return $lastUserId;
        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }

    public static function find_by_id(string $id): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = ORM::get_db()->prepare($sql);
            $stmt->execute(['id' => $id]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                $user = new User(
                    $userData['nombre'],
                    $userData['apellido_materno'],
                    $userData['apellido_paterno'],
                    $userData['email'],
                    $userData['password'],
                    $userData['id']
                );
                return $user;
            } else {
                return null;
            }
        } catch (PDOException $e) {

            throw new Exception("Error al buscar por ID: " . $e->getMessage());
        }
    }

    public static function find_by_email(string $email): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = ORM::get_db()->prepare($sql);
            $stmt->execute(['email' => $email]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                $user = new User(
                    $userData['nombre'],
                    $userData['apellido_materno'],
                    $userData['apellido_paterno'],
                    $userData['email'],
                    $userData['password'],
                    $userData['id']
                );
                return $user;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception("Error al buscar por email: " . $e->getMessage());
        }
    }
}
