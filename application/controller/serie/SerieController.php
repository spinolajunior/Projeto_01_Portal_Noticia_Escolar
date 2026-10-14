<?php

namespace controller\serie;

use controller\Controller;
use model\serie\Serie;

abstract class SerieController extends Controller
{
    public static function getById() {}

    public static function serieById(int $id): String|bool
    {

        $obj = new Serie();
        $obj->id = $id;
        $consulta = $obj->getById();
        return ($consulta) ? $consulta->nome : false;
    }

    public static function get(){
        return new Serie()->get();
    }
}
