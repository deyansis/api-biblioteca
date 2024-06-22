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
            $comentario_insert->documento_id = $comentario_data->documento_id;
            $comentario_insert->user_id = $comentario_data->user_id;
            $comentario_insert->save();
            ORM::get_db()->commit();

            return $comentario_insert->id();

        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }
}
