<?php

namespace DAOs\administrador;

use model\administrador\Administrador;

abstract class AdministradorDAO
{

    public function insert(Administrador $model): Administrador
    {
        return $model;
    }
    public static function update(Administrador $model): Administrador
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Administrador $model): Administrador
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
