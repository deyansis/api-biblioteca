<?php

declare(strict_types=1);

namespace App\Services;

require_once 'src/models/comentario.model.php';

use App\Models\Comentario;
use ORM;
use PDOException;
use Exception;

class Comentario_Services
{
    public static function create_comentario(Comentario $comentario_data): string
    {
        try {

            ORM::get_db()->beginTransaction();
            $comentario_insert = ORM::for_table('comentario')->create();
            $comentario_insert->comentario = $comentario_data->comentario;
            $comentario_insert->nombre_usuario = $comentario_data->nombre_usuario;
            $comentario_insert->save();
            ORM::get_db()->commit();

            return $comentario_insert->id();
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }

    public static function get_all_comentarios(): array
    {
        try {
            $comentarios = ORM::for_table('comentario')->find_many();
            $result = array_map(fn ($comentario) => new Comentario(
                id: $comentario->id,
                comentario: $comentario->comentario,
                nombre_usuario: $comentario->nombre_usuario
            ), $comentarios);
            return $result;
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }
}
