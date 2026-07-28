<?php

namespace DAOs\contato;

use model\contato\Contato;

abstract class ContatoDAO
{

    public function insert(Contato $model): Contato
    {
        return $model;
    }
    public static function update(Contato $model): Contato
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Contato $model): Contato
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
