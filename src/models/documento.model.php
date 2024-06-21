<?php

declare(strict_types=1);

namespace App\Models;

class Documento {
    public function __construct(
        public readonly string $autor,
        public readonly string $nombre_archivo,
        public readonly string $year,
        public readonly string $archivo_base64,
        public readonly int $id_user,
        public readonly ?int $id = null
    ){}
}