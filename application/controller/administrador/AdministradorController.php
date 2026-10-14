<?php

namespace controller\administrador;

use controller\Controller;
use controller\LoginController;
use model\Administrador\Administrador;
use view\View;

abstract class AdministradorController extends Controller
{

    public static function get(): void {
        
    }

    public static function getById(int $id): Administrador|bool
    {
        $obj = new Administrador();
        $obj->id = $id;
        $resultado = $obj->get();
        return (is_object($resultado))? $resultado : false;
    }


    public static function insert(): void
    {
        LoginController::logadoRedirect(null);
        new View("CADASTRO ADM!", View::$logado, VIEW . "include/forms/create/cadastrar_adm.php", null)->renderizar();
    }

    public static function update(): void
    {
        self::logadoRedirect();
        new View("Editar ADM", View::$logado, VIEW . "include/forms/update/update_adm.php", null)->renderizar();
    }

    public static function delete(): void {}
}
