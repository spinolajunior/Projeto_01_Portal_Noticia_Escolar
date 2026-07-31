<?php

namespace model\credenciais;

use DAOs\credenciais\CredenciaisDAO;

final class Credenciais{
    public ?int $id = null;
    public string $usuario;
    public string $senha;
    public string $criado_em;
    public ?string $last_login;
    public bool $ativo;

    public function getAll() : array{
        return new CredenciaisDAO()->getAll();
    }

}