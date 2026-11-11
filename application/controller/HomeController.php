<?php

namespace controller;

use controller\noticia\NoticiaController;
use controller\evento\EventoController;
use controller\aviso\AvisoController;
use view\View;

abstract class HomeController extends Controller
{
    public static function index(): void
    {
        $lista = array_values(array_filter(NoticiaController::getALL() ?: [], static fn($noticia) => (bool)$noticia->status));
        $paginas = array_chunk($lista, 3);
        $parametro = $_GET['pagina'] ?? '1';
        $pagina = is_string($parametro) && ctype_digit($parametro) ? (int)$parametro : 1;
        $pagina = max(1, min($pagina, max(1, count($paginas))));
        new View('Portal CETIDAM', View::$nav_footer, View::$dinamico . 'noticias_avisos.php', [
            'noticias' => $paginas[$pagina - 1] ?? [],
            'destaques' => array_slice($lista, 0, 3),
            'pagina' => $pagina,
            'totalPaginas' => count($paginas),
            'avisos' => array_values(array_filter(AvisoController::getAll(), static fn($aviso) => (bool)$aviso->ativo)),
            'eventos' => array_values(array_filter(EventoController::getAll(), static fn($evento) => $evento->data_evento >= date('Y-m-d'))),
        ])->renderizar();
    }

    public static function paginaNoticiaEventoAviso(): void
    {
        LoginController::logadoRedirect('/painel/noticia_evento_aviso');
        self::verificarAcesso((object)$_SESSION, 1);
        new View('Notícias, Eventos e Avisos', View::$nav_footer, View::$estatico . 'not_eve_avi.php', null)->renderizar();
    }

    public static function paginaGerenciamento(): void
    {
        self::verificarGestor();
        new View('Painel de Gerenciamento', View::$nav_footer, View::$dinamico . 'gerenciamento.php', null)->renderizar();
    }

    public static function erro(int $codigo, string $mensagem): void
    {
        http_response_code($codigo);
        new View('Portal CETIDAM', View::$nav_footer, View::$estatico . 'error_inesperado.php', ['mensagem' => $mensagem, 'codigo' => $codigo])->renderizar();
    }

    public static function emDesenvolvimento(): void
    {
        new View('Em desenvolvimento', View::$nav_footer, View::$estatico . 'error_inesperado.php', ['mensagem' => 'Esta página está em desenvolvimento.'])->renderizar();
    }

    public static function not_found(): void
    {
        http_response_code(404);
        new View('Página não encontrada', View::$nav_footer, View::$estatico . 'error_404.php', null)->renderizar();
    }
}
