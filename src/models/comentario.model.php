<?php

declare(strict_types=1);

namespace App\Models;

class Comentario {
    public function __construct(
        public readonly string $comentario,
        public readonly string $nombre_usuario,
        public readonly ?int $id = null
    ){}
}