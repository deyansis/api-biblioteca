<?php

declare(strict_types=1);

namespace App\Models;


class Carrera
{
    public function __construct(
        public readonly string $carrera,
        public readonly ?int $id = null
    ) {
    }

};

