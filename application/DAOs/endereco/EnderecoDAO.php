<?php

namespace DAOs\aviso;

use model\endereco\Endereco;

abstract class EnderecoDAO
{

    public function insert(Endereco $model): Endereco
    {
        return $model;
    }
    public static function update(Endereco $model): Endereco
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Endereco $model): Endereco
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
