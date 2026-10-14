<?php

namespace model\Administrador;

use DAOS\administrador\AdministradorDAO;
use DAOs\usuario\UsuarioDAO;

final class Administrador
{
    public ?int $id = null;

    public ?string $foto = null;
    public string $nome;
    public string $matricula;
    public ?int $id_credenciais = null;
    public ?int $id_tipo_adm = null;
    public ?int $ativo = null;

    public ?array $array = null;
    public function get(): Administrador|bool
    {
        return new AdministradorDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new AdministradorDAO()->getAll();
    }
    public function update(): Administrador|bool
    {
        return new AdministradorDAO()->update($this);
    }

    public function delete(): Administrador|bool
    {
        return new AdministradorDAO()->delete($this->id);
    }

    public function insert(): Administrador|bool
    {
        return new AdministradorDAO()->insert($this);
    }

    public function idCredConf(): Administrador|bool
    {
        return new AdministradorDAO()->idCredConf($this->id);
    }
    public function admStatus(): array|bool
    {
        return new AdministradorDAO()->admStatus();
    }

    public function atualizarDados():bool{
        return new UsuarioDAO()->atualizarAdm($this->array);
    }
}
