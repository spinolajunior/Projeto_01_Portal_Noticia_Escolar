<?php

namespace controller\tipo_Adm;

use controller\Controller;
use model\tipo_adm\Tipo_adm;

abstract class TipoAdmController extends Controller
{

    public static function getById() {}
    public static function get() {}
    public static function insert() {}
    public static function update() {}
    public static function delte() {}

    public static function adm_acesso(int $id): array|bool
    {
        $obj = new Tipo_adm();
        $obj->id = $id;
        $consulta = $obj->get();
        return ($consulta) ? [
            "cargo" => $consulta->cargo,
            "acesso" => $consulta->nivel_acesso
        ] : false;
    }
}
