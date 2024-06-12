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

function add_foreign_key(string $table, string $column, string $foreignTable, string $foreignColumn): void
{
    $fkName = "fk_{$table}_{$column}";

    $checkSql = "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table' AND CONSTRAINT_NAME = '$fkName'";
    $result = ORM::get_db()->query($checkSql);

    if ($result->rowCount() === 0) {
        $sql = "ALTER TABLE $table ADD CONSTRAINT $fkName FOREIGN KEY ($column) REFERENCES $foreignTable($foreignColumn)";
        ORM::get_db()->exec($sql);
    } 
}
