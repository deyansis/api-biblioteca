<?php

declare(strict_types=1);

namespace App\Models;


class User
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $apellido_materno,
        public readonly string $apellido_paterno,
        public readonly string $email,
        public readonly string $password,
        public readonly ?int $id = null
    ) {
    }

};

