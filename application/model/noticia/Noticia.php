<?php

namespace model\Noticia;

use DAOs\noticia\NoticiaDAO;

final class Noticia
{
    public ?int $id = null;
    public string $titulo;
    public string $subtitulo;
    public string $descricao;
    public ?string $imagem;
    public string $data_pub;
    public bool $status;
    public ?int $id_administrador = null;
    public ?string $nome_autor = null;

    public function get(): Noticia|bool
    {
        return new NoticiaDAO()->get($this->id);
    }
    public function getAll(): array
    {
        return new NoticiaDAO()->getAll();
    }
    public function update(): Noticia|bool
    {
        return new NoticiaDAO()->update($this);
    }

    public function delete(): bool
    {
        return new NoticiaDAO()->delete($this->id);
    }

    public function insert(): Noticia|bool
    {
        return new NoticiaDAO()->insert($this);
    }
    public function setImg(): Noticia|bool
    {
        return new NoticiaDAO()->setImg($this);
    }
    public function noticia_autor_all() : array|bool {
        return new NoticiaDAO()->noticia_autor_all();
    }
    
}
