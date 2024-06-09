<?php

declare(strict_types=1);

namespace App\Models;

require_once BASE_PATH . 'src/utils/orm.php';
require_once BASE_PATH . 'src/utils/password_bcrypt.php';




interface IUserRepository
{
    public static function create_table(): void;
    public static function create(User $user): string;
    public static function find_by_id(string $id): ?User;
    public static function find_by_email(string $email): ?User;

}



use function App\Utils\create_table_orm;
use App\Utils\PasswordUtil;
use ORM;
use PDOException;
use PDO;
use Exception;

class UserRepository  implements IUserRepository
{

    public static function create_table(): void
    {
        $columns = [
            'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
            'nombre' => 'VARCHAR(255)',
            'apellido_materno' => 'VARCHAR(255)',
            'apellido_paterno' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255) UNIQUE',
            'password' => 'VARCHAR(255)',
        ];

        create_table_orm('users', $columns);
    }

    public static function create(User $user): string
    {

        $encrypted_password = PasswordUtil::encryptPassword($user->password);

        $sql = "INSERT INTO users (nombre, apellido_materno, apellido_paterno, email, password) 
                VALUES (:nombre, :apellido_materno, :apellido_paterno, :email, :password)";
        $stmt = ORM::get_db()->prepare($sql);


        try {
            $stmt->execute([
                'nombre' => $user->nombre,
                'apellido_materno' => $user->apellido_materno,
                'apellido_paterno' => $user->apellido_paterno,
                'email' => $user->email,
                'password' => $encrypted_password,
            ]);

            return ORM::get_db()->lastInsertId();
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
