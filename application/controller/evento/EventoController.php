<?php

namespace controller\evento;

use controller\Controller;
use controller\LoginController;
use controller\LogController;
use model\evento\Evento;
use view\Flash_msg;
use view\View;

abstract class EventoController extends Controller
{
    public static function getAll(): array
    {
        return new Evento()->get() ?: [];
    }

    public static function painelEvento(): void
    {
        LoginController::logadoRedirect('/painel/evento');
        self::verificarAcesso((object)$_SESSION, 1);
        $lista = array_values(array_filter(new Evento()->get() ?: [], static fn($obj) => self::podeGerenciarPublicacao($obj)));
        new View('Gerenciar eventos', View::$nav_footer, View::$dinamicoPaineis . 'painel_evento.php', ['eventos' => $lista])->renderizar();
    }

    public static function insert(): void
    {
        LoginController::logadoRedirect('/cadastro/evento');
        self::verificarAcesso((object)$_SESSION, 1);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Cadastrar evento', View::$nav_footer, View::$formCreate . 'cadastrar_evento.php', null)->renderizar();
            return;
        }
        $obj = new Evento();
        $obj->titulo = self::campoPost('titulo', 5, 50);
        $obj->data_evento = self::campoPost('data_evento', 10, 10);
        self::validarData($obj->data_evento, true);
        if ($_SESSION['tipo_usuario'] === 'aluno') {
            $obj->id_aluno_adm = (int)$_SESSION['id_usuario'];
        } else {
            $obj->id_administrador = (int)$_SESSION['id_usuario'];
        }
        $resultado = $obj->insert();
        LogController::registrar('evento_cadastrado', $resultado ? 'sucesso' : 'falha', 'evento', $resultado ? $resultado->id : null);
        new Flash_msg($resultado ? 201 : 501)->flashMsg();
        header('Location: /painel/evento');
        exit;
    }

    public static function update(): void
    {
        LoginController::logadoRedirect($_SERVER['REQUEST_URI']);
        self::verificarAcesso((object)$_SESSION, 1);
        $obj = new Evento();
        $obj->id = self::idRequisicao();
        $obj = $obj->getById();
        self::autorizarPublicacao($obj);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Editar evento', View::$nav_footer, View::$formEdit . 'edit_evento.php', ['evento' => $obj])->renderizar();
            return;
        }
        $obj->titulo = self::campoPost('titulo', 5, 50);
        $obj->data_evento = self::campoPost('data_evento', 10, 10);
        self::validarData($obj->data_evento, true);
        $ok = $obj->update();
        LogController::registrar('evento_alterado', $ok ? 'sucesso' : 'falha', 'evento', $obj->id);
        new Flash_msg($ok ? 202 : 501)->flashMsg();
        header('Location: /painel/evento');
        exit;
    }

    public static function delete(): void
    {
        LoginController::logadoRedirect('/painel/evento');
        self::verificarAcesso((object)$_SESSION, 1);
        $obj = new Evento();
        $obj->id = self::idRequisicao();
        $obj = $obj->getById();
        self::autorizarPublicacao($obj);
        $ok = $obj->delete();
        LogController::registrar('evento_excluido', $ok ? 'sucesso' : 'falha', 'evento', $obj->id);
        new Flash_msg($ok ? 203 : 501)->flashMsg();
        header('Location: /painel/evento');
        exit;
    }
}
