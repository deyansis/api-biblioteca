<?php

declare(strict_types=1);
namespace App\Utils;

use ORM;


function create_table_orm(string $tableName, array $columns)
{
    $columnDefs = [];
    foreach ($columns as $column => $definition) {
        $columnDefs[] = "$column $definition";
    }
    $columnDefsStr = implode(", ", $columnDefs);
    $sql = "CREATE TABLE IF NOT EXISTS $tableName ($columnDefsStr)";
    ORM::get_db()->exec($sql);
}
