<?php

namespace model\aviso;

use DAOs\aviso\AvisoDAO;

final class Aviso
{

    public ?int $id = null;
    public string $titulo;
    public string $descricao;
    public string $data_aviso;
    public string $validade;
    public int $prioridade;
    public bool $status;
    public ?int $id_administrador = null;

    public function get(): Aviso|bool
    {
        return new AvisoDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new AvisoDAO()->getAll();
    }
    public function update(): Aviso|bool
    {
        return new AvisoDAO()->update($this);
    }

    public function delete(): Aviso|bool
    {
        return new AvisoDAO()->delete($this->id);
    }

    public function insert(): Aviso|bool
    {
        return new AvisoDAO()->insert($this);
    }
}
