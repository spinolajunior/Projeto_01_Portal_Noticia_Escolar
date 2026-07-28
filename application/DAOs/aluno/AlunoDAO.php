<?php

namespace DAOs\aluno;

use model\aluno\Aluno;

abstract class AlunoDAO
{

    public function insert(Aluno $model): Aluno
    {
        return $model;
    }
    public static function update(Aluno $model): Aluno
    {
        return $model;
    }
    public static function delete(int $id): bool
    {
        return true;
    }
    public static function get(Aluno $model): Aluno
    {
        return $model;
    }
    public static function getAll(): array
    {
        return [];
    }
}
