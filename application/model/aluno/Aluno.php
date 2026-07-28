<?php

namespace model\Aluno;

final class Aluno{
    public ?int $id = null;
    public string $nome;
    public string $matricula;
    public string $data_nascimento;
    public string $serie;
    public ?int $id_credenciais = null;

}