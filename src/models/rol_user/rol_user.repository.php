<?php

declare(strict_types=1);

namespace App\Models;

use ORM;
use PDOException;
use PDO;
use Exception;



interface IRolUserRepository
{
    public static function find_role_by_id_user(string $user_id): RolUser | bool;
}

class RolUserRepository implements IRolUserRepository
{

    // public static function create(RolUser $role): string
    // {

    //     try {
    //         ORM::get_db()->beginTransaction();

    //         $roler_user = ORM::for_table('user_roles')->create();
    //         $roler_user->


    //     } catch (PDOException $e) {
    //         throw new Exception("Error de servidor: " . $e->getMessage());
    //     }

    // }

    public static function find_role_by_id_user(string $user_id): RolUser | bool
    {

        try {
            ORM::get_db()->beginTransaction();

            $role_user = ORM::for_table('user_role')->where('user_id', $user_id)->find_one();

            if (!$role_user) {
                throw new Exception('No se encontro registro');
            }

            $role =  ORM::for_table("role")->find_one($role_user->role_id) ;

            if (!$role) {
                throw new Exception("No se encotro  role");
            }

            ORM::get_db()->commit();

            return $role;


        } catch (PDOException $e) {
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }



}

