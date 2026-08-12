<?php

namespace model\credenciais;

use DAOs\credenciais\CredenciaisDAO;

final class Credenciais
{
    public ?int $id = null;
    public string $usuario;
    public string $senha;
    public string $criado_em;
    public ?string $last_login;
    public bool $ativo;

    public function get(): Credenciais|bool
    {
        return new CredenciaisDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new CredenciaisDAO()->getAll();
    }
    public function update(): Credenciais|bool
    {
        return new CredenciaisDAO()->update($this);
    }

    public function delete(): Credenciais|bool
    {
        return new CredenciaisDAO()->delete($this->id);
    }

    public function insert(): Credenciais|bool
    {
        return new CredenciaisDAO()->insert($this);
    }

    public function logar(): Credenciais|bool
    {
        return new CredenciaisDAO()->logar($this);
    }
}
