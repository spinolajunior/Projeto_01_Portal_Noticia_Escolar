<?php

namespace model\Escola;
use DAOs\escola\EscolaDAO;

final class Escola
{
    public ?int $id = null;
    public string $nome;
    public string $cod_inep;
    public string $ano_letivo;
    public string $logo_img;
    public ?int $id_contato;
    public ?int $id_endereco;

    public function get(): Escola|bool
    {
        return new EscolaDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new EscolaDAO()->getAll();
    }
    public function update(): Escola|bool
    {
        return new EscolaDAO()->update($this);
    }

    public function delete(): Escola|bool
    {
        return new EscolaDAO()->delete($this->id);
    }

    public function insert(): Escola|bool
    {
        return new EscolaDAO()->insert($this);
    }
}
