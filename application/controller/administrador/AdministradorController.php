<?php

namespace controller\administrador;

use controller\Controller;
use controller\LoginController;
use controller\UsuarioController;
use model\administrador\Administrador;
use view\View;

abstract class AdministradorController extends Controller
{

    public static function get(): void {
        UsuarioController::gerenciarUsuario();
    }

    public static function getById(int $id): Administrador|bool
    {
        self::verificarGestor();
        $obj = new Administrador();
        $obj->id = $id;
        $resultado = $obj->get();
        return $resultado !== false && self::podeGerenciarUsuario($resultado, 'administrador') ? $resultado : false;
    }


    public static function insert(): void
    {
        UsuarioController::cadastroUsuario();
    }

    public static function update(): void
    {
        UsuarioController::editarAdm();
    }

    public static function delete(): void { UsuarioController::excluir(); }
}
