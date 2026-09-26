<?php

namespace model\evento;

use DAOs\evento\EventoDAO;

final class Evento
{

    public ?int $id = null;
    public string $titulo;
    public string $data_evento;
    public int $id_administrador;

    public function get(): array|bool
    {
        return new EventoDAO()->get();
    }
    public function getById(): Evento|bool
    {
        return new EventoDAO()->getById($this->id);
    }

    public function insert(): Evento|bool
    {
        return new EventoDAO()->insert($this);
    }

    public function update(): Evento|bool
    {
        return new EventoDAO()->update($this);
    }
    public function delete(): bool
    {
        return new EventoDAO()->delete($this->id);
    }
}
