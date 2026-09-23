<?php

namespace controller;

use controller\Controller;
use controller\noticia\NoticiaController;
use view\View;

abstract class HomeController extends Controller
{
    public static function index()
    {
        $pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
        $indice = $pagina - 1;
        $lista = NoticiaController::getALL();
        $paginas = array_chunk($lista, 3);
        if ($indice < 0 || $indice >= count($paginas)) {
            $indice = 0;
        }

        new View(
            "Portal CETIDAM",
            View::$nav_footer,
           View::$dinamico."noticias_avisos.php",
            $lista !== false ?
                $model = [
                    "noticias" => $paginas[$indice] ?? [],
                    "destaques" => array_slice($paginas[$indice] ?? [], 0, 3),
                    "pagina" => $pagina < 1 ? 1 : $pagina,
                    "totalPaginas" => count($paginas)
                ]
                : []
        )->renderizar();
    }

    public static function paginaNoticiaEventoAviso()
    {
        new View("Painel Noticia , Evento e Aviso!", View::$nav_footer, VIEW . "include/pages/estatico/not_eve_avi.php", null)->renderizar();
    }

    public static function paginaGerenciamento()
    {
        LoginController::logadoRedirect("/painel/gerenciamento");
        new View("Painel Gerenciamento!", View::$nav_footer, View::$dinamico . "gerenciamento.php", null)->renderizar();
    }
}
