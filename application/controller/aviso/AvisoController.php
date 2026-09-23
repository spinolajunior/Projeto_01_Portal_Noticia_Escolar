<?php

namespace controller\aviso;

use controller\Controller;
use controller\LoginController;
use view\View;

abstract class AvisoController extends Controller
{



    public static function painelAviso()
    {
        LoginController::logadoRedirect("/painel/aviso");
        new View("Painel Gerenciamento Aviso!", View::$nav_footer, View::$dinamicoPaineis . "painel_aviso.php", null)->renderizar();
    }
}
