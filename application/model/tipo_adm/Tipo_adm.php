<?php

namespace model\tipo_adm;
use DAOs\tipo_adm\TipoAdmDAO;

final class TipoAdm
{
    public ?int $id = null;
    public string $cargo;
    public int $nivel_acesso;

    public function get(): TipoAdm|bool
    {
        return new TipoAdmDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new TipoAdmDAO()->getAll();
    }
    public function update(): TipoAdm|bool
    {
        return new TipoAdmDAO()->update($this);
    }

    public function delete(): TipoAdm|bool
    {
        return new TipoAdmDAO()->delete($this->id);
    }

    public function insert(): TipoAdm|bool
    {
        return new TipoAdmDAO()->insert($this);
    }
}
