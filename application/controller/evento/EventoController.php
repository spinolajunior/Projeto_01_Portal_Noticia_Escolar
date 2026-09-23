<?php

namespace controller\evento;

use controller\LoginController;
use controller\Controller;
use view\View;

abstract class EventoController extends Controller
{

    public static function painelEvento(): void {
        LoginController::logadoRedirect("/painel/evento");
        new View("Painel Gerenciamento Evento!",VIEW::$nav_footer,VIEW::$dinamicoPaineis."painel_evento.php",null)->renderizar();
    }
}
