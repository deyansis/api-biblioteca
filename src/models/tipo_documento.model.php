<?php

declare(strict_types=1);

namespace App\Models;


class Tipo_Documento
{
    public function __construct(
        public readonly string $tipo_documento,
        public readonly ?int $id = null
    ) {
    }

};

