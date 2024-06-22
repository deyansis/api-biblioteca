<?php

declare(strict_types=1);

namespace App\Services;

require_once 'src/models/tipo_documento.model.php';

use App\Models\Tipo_Documento;
use App\Models\Documento;
use ORM;
use PDOException;
use Exception;


class Documento_Services
{
    public static function get_all_tipos(): array
    {

        try {
            $tipoDocumentos = ORM::for_table('tipo_documento')->find_many();
            $result = array_map(fn ($tipoDocumento) => new Tipo_Documento(
                id: $tipoDocumento->id,
                tipo_documento: $tipoDocumento->tipo_documento
            ), $tipoDocumentos);
            return $result;
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }

    public static function create_documento(Documento $documento_data): string
    {
        try {
            ORM::get_db()->beginTransaction();
            $documento = ORM::for_table('documento')->create();
            $documento->autor = $documento_data->autor;
            $documento->nombre_archivo = $documento_data->nombre_archivo;
            $documento->year = $documento_data->year;
            $documento->estado = $documento_data->estado;
            $documento->archivo_base64 = $documento_data->archivo_base64;
            $documento->user_id = $documento_data->user_id;
            $documento->carrera_id = $documento_data->carrera_id;
            $documento->tipo_documento_id = $documento_data->tipo_documento_id;
            $documento->save();
            ORM::get_db()->commit();

            return $documento->id;
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }

    public static function update_estado(int $documento_id, string $estado): string
    {

        try {
    
            ORM::get_db()->beginTransaction();
            $documento = ORM::for_table('documento')->find_one($documento_id);

            if (!$documento) {
                throw new Exception("No se encontró el documento con id: $documento_id");
            }

            $documento->set('estado', $estado);
            $documento->save();

            ORM::get_db()->commit();

            return (string)$documento->id;
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }
}
