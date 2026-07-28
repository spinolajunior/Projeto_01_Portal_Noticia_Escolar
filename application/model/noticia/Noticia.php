<?php

namespace model\Noticia;

final class Noticia{
    public ?int $id = null;
    public string $titulo;
    public string $subtitulo;
    public string $descricao;
    public string $imagem;
    public string $data_pub;
    public bool $status;
    public ?int $id_administrador = null;



}