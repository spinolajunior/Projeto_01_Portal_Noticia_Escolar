<?php

namespace model\Credenciais;

final class Credenciais{
    public ?int $id = null;
    public string $usuario;
    public string $senha;
    public string $criado_em;
    public string $last_login;
    public bool $ativo;



}