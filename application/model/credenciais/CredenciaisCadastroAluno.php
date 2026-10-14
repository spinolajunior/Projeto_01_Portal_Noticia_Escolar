<?php

namespace model\credenciais;
use DAOs\credenciais\CredenciaisDAO;

final class CredenciaisCadastroAluno
{

public string $usuario;
public string $senha;
public string $nome;
public string $matricula;
public string $data_nascimento;
public int $id_serie; 
    public function __construct(string $usuario, string $senha, string $nome, string $matricula, string $data_nascimento, int $id_serie)
    {
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->data_nascimento = $data_nascimento;
        $this->id_serie = $id_serie;
    }

    public function cadastrarAluno(): bool{
        return new CredenciaisDAO()->cadastroAluno($this);
    }
}
