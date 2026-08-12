<?php

namespace model\endereco;

use DAOs\endereco\EnderecoDAO;

final class Endereco
{
    public ?int $id = null;
    public string $cidade;
    public string $cep;
    public string $bairro;
    public string $rua;
    public string $complemento;

    public function get(): Endereco|bool
    {
        return new EnderecoDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new EnderecoDAO()->getAll();
    }
    public function update(): Endereco|bool
    {
        return new EnderecoDAO()->update($this);
    }

    public function delete(): Endereco|bool
    {
        return new EnderecoDAO()->delete($this->id);
    }

    public function insert(): Endereco|bool
    {
        return new EnderecoDAO()->insert($this);
    }
}
