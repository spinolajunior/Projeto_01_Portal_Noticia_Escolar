<?php

namespace model\Administrador;

final class Administrador{
    public ?int $id = null;
    public string $nome;
    public string $matricula;
    public string $cpf;
    public ?int $id_credenciais = null;
    public ?int $id_tipo_adm = null;
    public ?int $id_contato = null;
    public ?int $id_endereco = null;

}