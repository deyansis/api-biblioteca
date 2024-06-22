<?php

declare(strict_types=1);

namespace App\Models;

class Comentario {
    public function __construct(
        public readonly string $comentario,
        public readonly int $documento_id,
        public readonly int $user_id,
        public readonly ?int $id = null
    ){}
}