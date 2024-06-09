<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

try {

    $dotenv = Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
} catch (InvalidPathException $e) {
}
