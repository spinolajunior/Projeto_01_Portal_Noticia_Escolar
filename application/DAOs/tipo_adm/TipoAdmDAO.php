<?php

namespace DAOs\tipo_adm;

use model\tipo_adm\TipoAdm;

abstract class TipoAdmDAO
{

    public static function insert(TipoAdm $model): TipoAdm
    {
        return $model;
    }
    public static function update(TipoAdm $model): TipoAdm
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(TipoAdm $model): TipoAdm
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
