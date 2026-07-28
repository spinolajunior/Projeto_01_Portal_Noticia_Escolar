<?php

namespace DAOs\noticia;

use model\noticia\Noticia;

abstract class NoticiaDAO
{

    public static function insert(Noticia $model): Noticia
    {
        return $model;
    }
    public static function update(Noticia $model): Noticia
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Noticia $model): Noticia
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
