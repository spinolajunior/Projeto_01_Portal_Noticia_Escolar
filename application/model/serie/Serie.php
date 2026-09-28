<?php

namespace model\serie;
use DAOs\serie\SerieDAO;
final class Serie {
    public ?int $id = null;
    public string $nome;

    public function insert(){
    }
    public function getById(): Serie|bool{
        return new SerieDAO()->getById($this->id);
    }
    public function get() :array|bool{
        return new SerieDAO()->get();
    }
    public function update(){}
    public function delete(){}
}