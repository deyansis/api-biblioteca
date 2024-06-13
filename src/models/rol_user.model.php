<?php

declare(strict_types=1);

namespace App\Models;


class RolUser
{
    public function __construct(
        public readonly string $role ,
        public readonly ?int $id = null
    ) {
    }

};
