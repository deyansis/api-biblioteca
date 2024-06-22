<?php

declare(strict_types=1);

namespace App\Services;

require_once 'src/models/carrera.model.php';

use App\Models\Carrera;
use ORM;
use PDOException;
use Exception;


class Carrera_Services
{


    public static function create_carrera(string $carrera): string
    {
        try {
            $carrera = strtolower(trim($carrera));
        
            ORM::get_db()->beginTransaction();
            $carrera_insert = ORM::for_table('carrera')->create();
            $carrera_insert->carrera = $carrera;
            $carrera_insert->save();
            ORM::get_db()->commit();
    
            return $carrera_insert->id();
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }

    public static function get_all_carrera(): array
    {

        try {
            $carreras = ORM::for_table('carrera')->find_many();
            $result = array_map(fn ($carrera) => new Carrera(
                id: $carrera->id,
                carrera: $carrera->carrera
            ), $carreras);
            return $result;
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }
}
