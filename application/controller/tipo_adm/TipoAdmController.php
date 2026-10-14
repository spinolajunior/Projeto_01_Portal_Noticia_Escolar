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
    public static function aluno_nivel_acesso(int $id): int|false
    {
        $obj = new Tipo_adm();
        $obj->id = $id;
        $consulta = $obj->get();
        if (is_object($consulta)) {
            return $consulta->nivel_acesso;
        } else {
            return false;
        }
    }
}
