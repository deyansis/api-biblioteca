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

    public function user_without_password(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido_materno' => $this->apellido_materno,
            'apellido_paterno' => $this->apellido_paterno,
            'email' => $this->email,
        ];
    }
};

