<?php

namespace model\endereco;

final class Endereco{
    public ?int $id = null;
    public string $cidade;
    public string $cep;
    public string $bairro;
    public string $rua;
    public string $complemento;

}