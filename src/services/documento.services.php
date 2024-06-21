<?php

declare(strict_types=1);

namespace App\Services;

require_once 'src/models/tipo_documento.model.php';

use App\Models\Tipo_Documento;
use ORM;
use PDOException;
use Exception;


class Documento_Services
{
    public static function get_all_tipos(): array
    {

        try {
            $tipoDocumentos = ORM::for_table('tipo_documento')->find_many();
            $result = [];
            foreach ($tipoDocumentos as $tipoDocumento) {
                $result[] = new Tipo_Documento($tipoDocumento->id, $tipoDocumento->tipo_documento);
            }
            return $result;
        } catch (PDOException $e) {
            ORM::get_db()->rollBack();
            throw new Exception("Error de servidor: " . $e->getMessage());
        }
    }



}