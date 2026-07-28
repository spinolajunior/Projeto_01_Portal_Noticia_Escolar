<?php

namespace DAOs\escola;

use model\escola\Escola;

abstract class EscolaDAO
{

    public static function insert(Escola $model): Escola
    {
        return $model;
    }
    public static function update(Escola $model): Escola
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Escola $model): Escola
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
