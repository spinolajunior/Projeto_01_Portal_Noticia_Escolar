<?php

namespace controller;

use view\View;

abstract class UsuarioController extends Controller
{

    public static function perfil()
    {
        LoginController::logadoRedirect("/perfil");
        if (!isset($_SESSION['id_tipo_adm'])) {
            new View("Perfil", View::$nav_footer, View::$dinamico . "perfil_aluno.php", null)->renderizar();
        } else {
            new View("Perfil", View::$nav_footer, View::$dinamico . "perfil_administrador.php", null)->renderizar();
        }
    }

    public static function editarPerfil(){
        LoginController::logadoRedirect(null);

    }
}
