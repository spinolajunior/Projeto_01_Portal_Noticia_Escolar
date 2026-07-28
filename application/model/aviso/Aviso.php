<?php

namespace model\Aviso;

final class Aviso{

    public ?int $id = null;
    public string $titulo;
    public string $descricao;
    public string $data_aviso;
    public string $validade;
    public int $prioridade;
    public bool $status;
    public ?int $id_admnistrador = null;


}