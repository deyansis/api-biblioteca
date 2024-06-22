<?php

declare(strict_types=1);

namespace App\Utils;

use ReflectionClass;

function validateRequiredFields($data, $requiredFields)
{

    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missingFields[] = $field;
        }
    }
    
    return [
        'isValid' => empty($missingFields),
        'missingFields' => $missingFields
    ];
}

function validateRequiredFieldsFromClass($data, $className)
{
    $reflectionClass = new ReflectionClass($className);
    $constructor = $reflectionClass->getConstructor();
    $parameters = $constructor->getParameters();

    $missingFields = [];

    foreach ($parameters as $param) {
       
        if ($param->isOptional()) {
            continue;
        }

        $paramName = $param->getName();
        if (!isset($data[$paramName]) || empty($data[$paramName])) {
            $missingFields[] = $paramName;
        }
    }

    return [
        'isValid' => empty($missingFields),
        'missingFields' => $missingFields
    ];
}

function validateEmailValid($email){

    $regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    return preg_match($regex, $email);
}

function validateEstado($estado) {
    $allowedEstados = ['revision', 'rechazado', 'aprobado'];
    return in_array($estado, $allowedEstados);
}