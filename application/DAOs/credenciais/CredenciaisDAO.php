<?php

namespace DAOs\aviso;

use model\credenciais\Credenciais;

abstract class CredenciaisDAO
{

    public function insert(Credenciais $model): Credenciais
    {
        return $model;
    }
    public static function update(Credenciais $model): Credenciais
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Credenciais $model): Credenciais
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
