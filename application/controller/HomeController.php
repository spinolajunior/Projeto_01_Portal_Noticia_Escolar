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

        if (isset($_SESSION['usuario']) && isset($_SESSION['senha'])) {
            new View(
                "Pagina Principal",
                VIEW . "template/Nav_footer_out.php",
                VIEW . "include/Noticias_avisos.php",
                $lista !== false ?
                    $model = [
                        "noticias" => $paginas[$indice] ?? [],
                        "destaques" => array_slice($paginas[$indice] ?? [], 0, 3),
                        "pagina" => $pagina < 1 ? 1 : $pagina,
                        "totalPaginas" => count($paginas)
                    ]
                    : []
            )->renderizar();
        } else {
            new View(
                "Pagina Principal",
                VIEW . "template/Nav_footer_logado.php",
                VIEW . "include/Noticias_avisos.php",
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
    }

}
