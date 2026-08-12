<?php

namespace model\Aluno;

use DAOs\aluno\AlunoDAO;

final class Aluno
{
    public ?int $id = null;
    public string $nome;
    public string $matricula;
    public string $data_nascimento;
    public string $serie;
    public ?int $id_credenciais = null;

    public function get(): Aluno|bool
    {
        return new AlunoDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new AlunoDAO()->getAll();
    }
    public function update(): Aluno|bool
    {
        return new AlunoDAO()->update($this);
    }

    public function delete(): Aluno|bool
    {
        return new AlunoDAO()->delete($this->id);
    }

    public function insert(): Aluno|bool
    {
        return new AlunoDAO()->insert($this);
    }

    public function idCredConf() : Aluno|bool
    {
        return new AlunoDAO()->idCredConf($this->id);
    }
}
