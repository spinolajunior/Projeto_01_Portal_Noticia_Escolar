<?php

namespace controller\noticia;

use controller\Controller;
use controller\HomeController;
use controller\LoginController;
use controller\LogController;
use model\administrador\Administrador;
use model\aluno\Aluno;
use model\noticia\Noticia;
use model\credenciais\Credenciais;
use Throwable;
use InvalidArgumentException;
use view\Flash_msg;
use view\View;

abstract class NoticiaController extends Controller
{
    public static function getALL(): bool|array
    {
        return new Noticia()->getAll();
    }

    public static function insert(): void
    {
        LoginController::logadoRedirect('/cadastro/noticia');
        self::verificarAcesso((object)$_SESSION, 1);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Cadastrar notícia', View::$nav_footer, View::$formCreate . 'cadastrar_noticia.php', null)->renderizar();
            return;
        }
        $model = new Noticia();
        $model->titulo = self::campoPost('titulo', 5, 50);
        $model->subtitulo = self::campoPost('subtitulo', 0, 80);
        $model->descricao = self::campoPost('descricao', 1, 65535);
        $model->data_pub = date('Y-m-d H:i:s');
        $model->status = true;
        if ($_SESSION['tipo_usuario'] === 'aluno') {
            $model->id_aluno_adm = (int)$_SESSION['id_usuario'];
        } else {
            $model->id_administrador = (int)$_SESSION['id_usuario'];
        }
        if (!isset($_FILES['img'])) {
            throw new InvalidArgumentException('Selecione uma imagem válida para a notícia.');
        }
        $model->imagem = self::adicionarFoto($_FILES['img'], View::$uploadImagemNoticia, (string)$_SESSION['id_credencial']);
        try {
            $resultado = $model->insert();
            if ($resultado === false) {
                throw new \RuntimeException('Falha ao cadastrar notícia.');
            }
        } catch (Throwable $e) {
            self::removerFoto($model->imagem);
            throw $e;
        }
        LogController::registrar('noticia_cadastrada', 'sucesso', 'noticia', $resultado->id);
        new Flash_msg(201)->flashMsg();
        header('Location: /painel/noticia');
        exit;
    }

    public static function get(): void
    {
        $noticia = new Noticia();
        $noticia->id = self::idRequisicao();
        $noticia = $noticia->get();
        if ($noticia === false || !$noticia->status) {
            HomeController::not_found();
            return;
        }
        $autor = $noticia->id_administrador !== null ? new Administrador() : new Aluno();
        $autor->id = $noticia->id_administrador ?? $noticia->id_aluno_adm;
        $autor = $autor->id !== null ? $autor->get() : false;
        $foto = false;
        if ($autor !== false && $autor->id_credenciais !== null) {
            $credencial = new Credenciais();
            $credencial->id = $autor->id_credenciais;
            $foto = $credencial->buscarFotoAutorPubli();
        }
        new View($noticia->titulo, View::$nav_footer, View::$dinamico . 'noticia_id.php', ['noticia' => $noticia, 'adm' => $autor ?: (object)['nome' => 'Autor não disponível'], 'foto_autor' => $foto ?: null])->renderizar();
    }

    public static function update(): void
    {
        LoginController::logadoRedirect($_SERVER['REQUEST_URI']);
        self::verificarAcesso((object)$_SESSION, 1);
        $obj = new Noticia();
        $obj->id = self::idRequisicao();
        $obj = $obj->get();
        self::autorizarPublicacao($obj);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Editar notícia', View::$nav_footer, View::$formEdit . 'edit_noticia.php', ['noticia' => $obj, 'url_imagem' => $obj->imagem])->renderizar();
            return;
        }
        $obj->titulo = self::campoPost('titulo', 5, 50);
        $obj->subtitulo = self::campoPost('subtitulo', 0, 80);
        $obj->descricao = self::campoPost('descricao', 1, 65535);
        $status = self::campoPost('status', 1, 1);
        if (!in_array($status, ['0', '1'], true)) {
            throw new InvalidArgumentException('Status da notícia inválido.');
        }
        $obj->status = $status === '1';
        $imagemAnterior = $obj->imagem;
        $imagemNova = null;
        try {
            if (isset($_FILES['imagem']) && ($_FILES['imagem']['error'] ?? null) !== UPLOAD_ERR_NO_FILE) {
                $imagemNova = self::adicionarFoto($_FILES['imagem'], View::$uploadImagemNoticia, (string)$obj->id);
                $obj->imagem = $imagemNova;
            }
            if (!$obj->update()) {
                throw new \RuntimeException('Falha ao atualizar notícia.');
            }
        } catch (Throwable $e) {
            self::removerFoto($imagemNova);
            throw $e;
        }
        if ($imagemNova !== null) {
            self::removerFoto($imagemAnterior);
        }
        LogController::registrar('noticia_alterada', 'sucesso', 'noticia', $obj->id);
        new Flash_msg(202)->flashMsg();
        header('Location: /painel/noticia');
        exit;
    }

    public static function delete(): void
    {
        LoginController::logadoRedirect('/painel/noticia');
        self::verificarAcesso((object)$_SESSION, 1);
        $obj = new Noticia();
        $obj->id = self::idRequisicao();
        $obj = $obj->get();
        self::autorizarPublicacao($obj);
        $ok = $obj->delete();
        if ($ok) {
            self::removerFoto($obj->imagem);
        }
        LogController::registrar('noticia_excluida', $ok ? 'sucesso' : 'falha', 'noticia', $obj->id);
        new Flash_msg($ok ? 203 : 501)->flashMsg();
        header('Location: /painel/noticia');
        exit;
    }

    public static function painelNoticia(): void
    {
        LoginController::logadoRedirect('/painel/noticia');
        self::verificarAcesso((object)$_SESSION, 1);
        $noticias = array_values(array_filter(new Noticia()->noticia_autor_all() ?: [], static fn($noticia) => self::podeGerenciarPublicacao($noticia)));
        new View('Gerenciar notícias', View::$nav_footer, View::$dinamicoPaineis . 'painel_noticia.php', ['noticias' => $noticias])->renderizar();
    }
}
