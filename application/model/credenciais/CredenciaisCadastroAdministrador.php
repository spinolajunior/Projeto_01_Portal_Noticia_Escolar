<?php

namespace model\credenciais;
use DAOs\credenciais\CredenciaisDAO;

final class CredenciaisCadastroAdministrador
{

    public string $usuario;
    public string $senha;
    public string $nome;
    public string $matricula;
    public string $id_tipo_adm;

    public function __construct(string $usuario, string $senha, string $nome, string $matricula, int $id_tipo_adm)
    {

        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->id_tipo_adm = $id_tipo_adm;

    }

    public function cadastrarAdm(): bool
    {
        return new CredenciaisDAO()->cadastroAdm($this);
    }
}
