<?php

namespace controller\administrador;

use controller\Controller;
use view\View;

abstract class AdministradorController extends Controller
{

    public static function get(): void {
        
    }

    public static function getById(): void
    {
        self::logadoRedirect();

        new View("Perfil", View::$logado, VIEW . "include/Perfil_administrador.php", null)->renderizar();
    }


    public static function insert(): void
    {
        self::logadoRedirect();
        new View("CADASTRO ADM!", View::$logado, VIEW . "include/forms/create/cadastrar_adm.php", null)->renderizar();
    }

    public static function update(): void
    {
        self::logadoRedirect();
        new View("Editar ADM", View::$logado, VIEW . "include/forms/update/update_adm.php", null)->renderizar();
    }

    public static function delete(): void {}
}
