<?php

namespace model\Contato;

use DAOs\contato\ContatoDAO;

final class Contato
{
    public ?int $id = null;
    public string $tel;
    public string $email;

    public function get(): Contato|bool
    {
        return new ContatoDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new ContatoDAO()->getAll();
    }
    public function update(): Contato|bool
    {
        return new ContatoDAO()->update($this);
    }

    public function delete(): Contato|bool
    {
        return new ContatoDAO()->delete($this->id);
    }

    public function insert(): Contato|bool
    {
        return new ContatoDAO()->insert($this);
    }
}
