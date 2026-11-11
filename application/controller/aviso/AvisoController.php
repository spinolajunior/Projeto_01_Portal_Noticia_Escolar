<?php

namespace controller\aviso;

use controller\Controller;
use controller\LoginController;
use controller\LogController;
use model\aviso\Aviso;
use view\Flash_msg;
use view\View;

abstract class AvisoController extends Controller
{
    public static function getAll(): array
    {
        return new Aviso()->getAll();
    }

    public static function painelAviso(): void
    {
        LoginController::logadoRedirect('/painel/aviso');
        self::verificarAcesso((object)$_SESSION, 1);
        $lista = array_values(array_filter(new Aviso()->getAll() ?: [], static fn($obj) => self::podeGerenciarPublicacao($obj)));
        new View('Gerenciar avisos', View::$nav_footer, View::$dinamicoPaineis . 'painel_aviso.php', ['avisos' => $lista])->renderizar();
    }

    public static function insert(): void
    {
        LoginController::logadoRedirect('/cadastro/aviso');
        self::verificarAcesso((object)$_SESSION, 1);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Cadastrar aviso', View::$nav_footer, View::$formCreate . 'cadastrar_aviso.php', null)->renderizar();
            return;
        }
        $obj = new Aviso();
        $obj->titulo = self::campoPost('titulo', 1, 80);
        $obj->ativo = true;
        if ($_SESSION['tipo_usuario'] === 'aluno') {
            $obj->id_aluno_adm = (int)$_SESSION['id_usuario'];
        } else {
            $obj->id_administrador = (int)$_SESSION['id_usuario'];
        }
        $resultado = $obj->insert();
        LogController::registrar('aviso_cadastrado', $resultado ? 'sucesso' : 'falha', 'aviso', $resultado ? $resultado->id : null);
        new Flash_msg($resultado ? 201 : 501)->flashMsg();
        header('Location: /painel/aviso');
        exit;
    }

    public static function update(): void
    {
        LoginController::logadoRedirect($_SERVER['REQUEST_URI']);
        self::verificarAcesso((object)$_SESSION, 1);
        $obj = new Aviso();
        $obj->id = self::idRequisicao();
        $obj = $obj->get();
        self::autorizarPublicacao($obj);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Editar aviso', View::$nav_footer, View::$formEdit . 'edit_aviso.php', ['aviso' => $obj])->renderizar();
            return;
        }
        $obj->titulo = self::campoPost('titulo', 1, 80);
        $ativo = self::campoPost('ativo', 1, 1);
        if (!in_array($ativo, ['0', '1'], true)) {
            throw new \InvalidArgumentException('Status do aviso inválido.');
        }
        $obj->ativo = $ativo === '1';
        $ok = $obj->update();
        LogController::registrar('aviso_alterado', $ok ? 'sucesso' : 'falha', 'aviso', $obj->id);
        new Flash_msg($ok ? 202 : 501)->flashMsg();
        header('Location: /painel/aviso');
        exit;
    }

    public static function delete(): void
    {
        LoginController::logadoRedirect('/painel/aviso');
        self::verificarAcesso((object)$_SESSION, 1);
        $obj = new Aviso();
        $obj->id = self::idRequisicao();
        $obj = $obj->get();
        self::autorizarPublicacao($obj);
        $ok = $obj->delete();
        LogController::registrar('aviso_excluido', $ok ? 'sucesso' : 'falha', 'aviso', $obj->id);
        new Flash_msg($ok ? 203 : 501)->flashMsg();
        header('Location: /painel/aviso');
        exit;
    }
}
