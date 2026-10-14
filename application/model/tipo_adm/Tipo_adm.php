<?php

namespace model\tipo_adm;
use DAOs\tipo_adm\TipoAdmDAO;

final class Tipo_adm
{
    public ?int $id = null;
    public string $cargo;
    public int $nivel_acesso;

    public function get(): Tipo_adm|bool
    {
        return new TipoAdmDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new TipoAdmDAO()->getAll();
    }
    public function update(): Tipo_adm|bool
    {
        return new TipoAdmDAO()->update($this);
    }

    public function delete(): Tipo_adm|bool
    {
        return new TipoAdmDAO()->delete($this->id);
    }

    public function insert(): Tipo_adm|bool
    {
        return new TipoAdmDAO()->insert($this);
    }
}
