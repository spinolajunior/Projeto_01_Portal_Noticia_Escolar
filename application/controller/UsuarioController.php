<?php

namespace controller;

use model\Administrador\Administrador;
use model\Aluno\Aluno;
use model\serie\Serie;
use view\View;
use view\Flash_msg;
use controller\serie\SerieController;
use model\credenciais\Credenciais;
use model\credenciais\CredenciaisCadastroAdministrador;
use model\credenciais\CredenciaisCadastroAluno;
use Throwable;

abstract class UsuarioController extends Controller
{

    public static function perfil()
    {
        LoginController::logadoRedirect("/perfil");
        $model = ['session' => (object)$_SESSION];
        if (!isset($_SESSION["id_tipo_adm"])) {

            new View("Perfil", View::$nav_footer, View::$dinamico . "perfil_aluno.php", $model)->renderizar();
        } else {
            new View("Perfil", View::$nav_footer, View::$dinamico . "perfil_administrador.php", $model)->renderizar();
        }
    }

    public static function editarPerfil()
    {

        $session = (object)$_SESSION;
        LoginController::logadoRedirect("/atualizar/myself");
        LoginController::verificarAcesso($session, 1);


        if ($_SERVER['REQUEST_METHOD'] == 'GET') {


            if (isset($session->id_tipo_adm) && $session->id_tipo_adm != null) {
                // view adm
                $usuario = new Administrador();
                $usuario->id = $session->id_usuario;
                $usuario = $usuario->get();

                $credencial = new Credenciais();
                $credencial->id = $session->id_credencial;
                $credencial = $credencial->get();


                $model = [
                    "usuario" => $usuario,
                    "credencial" => $credencial
                ];
                new View("Atualizar Perfil!", View::$nav_footer, View::$formEdit . 'edit_myself_adm.php', $model)->renderizar();
            } else {
                //view aluno
                $usuario = new Aluno();
                $usuario->id = $session->id_usuario;
                $usuario = $usuario->get();

                $credencial = new Credenciais();
                $credencial->id = $session->id_credencial;
                $credencial = $credencial->get();

                $series = new Serie()->get();

                $model = [
                    "usuario" => $usuario,
                    "credencial" => $credencial,
                    "series" => $series
                ];
                new View("Atualizar Perfil!", View::$nav_footer, View::$formEdit . 'edit_myself_aluno.php', $model)->renderizar();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $post = (object)$_POST;

            $usuario = null;

            if (isset($session->id_tipo_adm)) {
                $usuario = new Administrador();
                $usuario->id = $post->id;
                $usuario->nome = $post->nome;
                $usuario->matricula = $post->matricula;
            } elseif (!isset($session->id_tipo_adm)) {
                $usuario = new Aluno();
                $usuario->id = $post->id;
                $usuario->data_nascimento = $post->data_nascimento;
                $usuario->id_serie = $post->id_serie;
                $usuario->nome = $post->nome;
                $usuario->matricula = $post->matricula;
            }

            ///
            $credencial = new Credenciais();
            $credencial->id = $post->id_credencial;
            $credencial->usuario = $post->usuario;
            $credencial->senha = $post->senha;
            ///

            $foto_update = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {


                try {

                    $foto_update = self::adicionarFoto($_FILES['foto'], BASE_DIR . 'view/img/upload/perfil_usuario', $post->id_credencial, 'perfil');
                } catch (Throwable $e) {
                    new Flash_msg(0, $e->getMessage())->flashMsg();
                    header('location: /atualizar/myself');
                    exit;
                }
            }

            $array = [
                'usuario' => $usuario,
                'credencial' => $credencial,
                'foto_update' => $foto_update
            ];

            $consulta = null;

            if (isset($session->id_tipo_adm)) {
                $consulta =  new Administrador();
                $consulta->array = $array;
            } elseif (!isset($session->id_tipo_adm)) {
                $consulta =  new Aluno();
                $consulta->array = $array;
            }

            if ($consulta->atualizarDados()) {

                if ($foto_update !== null && file_exists(preg_replace('~/+~', '/', BASE_DIR . $_SESSION['foto']))) {
                    unlink(preg_replace('~/+~', '/', BASE_DIR . $_SESSION['foto']));
                }
                self::atualizarSession();
                new Flash_msg(202)->flashMsg();
                header('location: /atualizar/myself');
                exit;
            } else {
                if ($foto_update !== null && file_exists(preg_replace('~/+~', '/', BASE_DIR . $foto_update))) {
                    unlink(preg_replace('~/+~', '/', BASE_DIR . $foto_update));
                }
                new Flash_msg(501)->flashMsg();
                header('location: /atualizar/myself');
                exit;
            }
        }
    }

    public static function cadastroUsuario()
    {
        LoginController::logadoRedirect("/cadastro/usuario");
        Controller::verificarAcesso((object)$_SESSION, 1);
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $model = ["series" => SerieController::get()];
            new View("Cadastro de Usuario!", View::$nav_footer, View::$formCreate . "cadastrar_usuario.php", $model)->renderizar();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {

            $post = (object)$_POST;

            if ((int)$post->tipo_usuario != 0) {

                $novo_adm = new CredenciaisCadastroAdministrador($post->usuario, $post->senha, $post->nome, $post->matricula, (int)$post->tipo_usuario);
                if ($novo_adm->cadastrarAdm()) {
                    new Flash_msg(201)->flashMsg();
                    header('location: /cadastro/usuario');
                    exit;
                } else {
                    new Flash_msg(409)->flashMsg();
                    header('location: /cadastro/usuario');
                    exit;
                }
            } else {

                $novo_aluno = new CredenciaisCadastroAluno($post->usuario, $post->senha, $post->nome, $post->matricula, $post->data_nascimento, (int)$post->id_serie);

                if ($novo_aluno->cadastrarAluno()) {
                    new Flash_msg(201)->flashMsg();
                    header('location: /cadastro/usuario');
                    exit;
                } else {
                    new Flash_msg(409)->flashMsg();
                    header('location: /cadastro/usuario');
                    exit;
                }
            }
        }
    }

    public static function gerenciarUsuario()
    {
        LoginController::logadoRedirect("/painel/usuarios");
        $adm = new Administrador()->getAll();
        $aluno = new Aluno()->getAll();

        $model = [
            "administradores" => $adm,
            "alunos" => $aluno
        ];
        new View("Gerencair Usuarios!", View::$nav_footer, View::$dinamicoPaineis . "painel_usuarios.php", $model)->renderizar();
    }

    public static function editarAluno()
    {
        LoginController::logadoRedirect("/atualizar/usuario/aluno" .
            ((isset($_GET['id'])) ? '&id=' . $_GET['id'] : ''));
        LoginController::verificarAcesso((object)$_SESSION, 1);
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $consulta = new Aluno();
            $consulta->id = $_GET['id'];
            $aluno = $consulta->get();
            $consulta = new Credenciais();
            $consulta->id = $aluno->id_credenciais;
            $credencial = $consulta->get();

            $model = [
                "series" => SerieController::get(),
                "aluno" => $aluno,
                "credenciais" => $credencial
            ];

            new View("Editar Aluno!", View::$nav_footer, View::$formEdit . "edit_aluno.php", $model)->renderizar();
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = (object)$_POST;

            $usuario = null;


            $usuario = new Aluno();
            $usuario->id = $post->id;
            $usuario->data_nascimento = $post->data_nascimento;
            $usuario->id_serie = $post->id_serie;
            $usuario->nome = $post->nome;
            $usuario->matricula = $post->matricula;


            $credencial = new Credenciais();
            $credencial->id = $post->id_credencial;
            $credencial->usuario = $post->usuario;
            $credencial->senha = $post->senha;


            $foto_update = null;

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {


                try {

                    $foto_update = self::adicionarFoto($_FILES['foto'], BASE_DIR . 'view/img/upload/perfil_usuario', $post->id_credencial, 'perfil');
                } catch (Throwable $e) {
                    new Flash_msg(0, $e->getMessage())->flashMsg();
                    header('location: /atualizar/aluno&id='.$usuario->id);
                    exit;
                }
            }

            $array = [
                'usuario' => $usuario,
                'credencial' => $credencial,
                'foto_update' => $foto_update
            ];

            $consulta =  new Aluno();
            $consulta->array = $array;

            if ($consulta->atualizarDados()) {

                if ($foto_update !== null && file_exists(preg_replace('~/+~', '/', BASE_DIR . $_SESSION['foto']))) {
                    unlink(preg_replace('~/+~', '/', BASE_DIR . $_SESSION['foto']));
                }
                self::atualizarSession();
                new Flash_msg(202)->flashMsg();
                header('location: /painel/usuarios');
                exit;
            } else {
                if ($foto_update !== null && file_exists(preg_replace('~/+~', '/', BASE_DIR . $foto_update))) {
                    unlink(preg_replace('~/+~', '/', BASE_DIR . $foto_update));
                }
                new Flash_msg(501)->flashMsg();
                header('location: /painel/usuarios');
                exit;
            }
        }
    }

    public static function editarAdm()
    {

        LoginController::logadoRedirect("/atualizar/usuario/administrador" .
            ((isset($_GET['id'])) ? '&id=' . $_GET['id'] : ''));
        LoginController::verificarAcesso((object)$_SESSION, 1);


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $post = (object)$_POST;
         
            $usuario = new Administrador();
            $usuario->id = $post->id;
            $usuario->nome = $post->nome;
            $usuario->matricula = $post->matricula;

            $credencial = new Credenciais();
            $credencial->id = $post->id_credenciais;
            $credencial->usuario = $post->usuario;
            $credencial->senha = $post->senha;

            $foto_update = null;

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

                try {

                    $foto_update = self::adicionarFoto($_FILES['foto'], BASE_DIR . 'view/img/upload/perfil_usuario', $post->id_credenciais, 'perfil');
                } catch (Throwable $e) {
                    new Flash_msg(0, $e->getMessage())->flashMsg();
                    header('location: /atualizar/usuario/administrador?id='.$usuario->id);
                    exit;
                }
            }

            $array = [
                'usuario' => $usuario,
                'credencial' => $credencial,
                'foto_update' => $foto_update
            ];

            $consulta =  new Administrador();
            $consulta->array = $array;

            if ($consulta->atualizarDados()) {

                if ($foto_update !== null && file_exists(preg_replace('~/+~', '/', BASE_DIR . $_SESSION['foto']))) {
                    unlink(preg_replace('~/+~', '/', BASE_DIR . $_SESSION['foto']));
                }
                self::atualizarSession();
                new Flash_msg(202)->flashMsg();
                header('location: /atualizar/usuario/administrador?id=' . $usuario->id);
                exit;
            } else {
                if ($foto_update !== null && file_exists(preg_replace('~/+~', '/', BASE_DIR . $foto_update))) {
                    unlink(preg_replace('~/+~', '/', BASE_DIR . $foto_update));
                }
                new Flash_msg(501)->flashMsg();
                header('location: /atualizar/usuario/administrador?id=' . $usuario->id);
                exit;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $consulta = new Administrador();
            $consulta->id = (int)$_GET['id'];
            $usuario = $consulta->get();
            $consulta = new Credenciais();
            $consulta->id = (int)$usuario->id_credenciais;
            $credencial = $consulta->get();
            $model = [
                "usuario" => $usuario,
                "credencial" => $credencial
            ];
            new View("Editar Administrador!", View::$nav_footer, View::$formEdit . "edit_administrador.php", $model)->renderizar();
        }
    }
    public static function updateAcessoUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $alunos = new Aluno()->alunoStatus();
            $adm = new Administrador()->admStatus();

            $model = [
                "alunos" => $alunos,
                "adm" => $adm
            ];

            new View("Acesso usuarios do sistema!", View::$nav_footer, View::$dinamicoPaineis . "painel_acesso_usuario.php", $model)->renderizar();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $post = (object)$_POST;
            var_dump($post);
            $credencial = new Credenciais();
            $credencial->id = $post->id_credenciais;
            $credencial->ativo = $post->ativo;

            if ($credencial->acesso()) {
                new Flash_msg(207)->flashMsg();
                header('location: /painel/acesso/usuario');
                exit;
            } else {
                new Flash_msg(501, 'Tente novamente!')->flashMsg();
                header('location: /painel/acesso/usuario');
            }
        }
    }

    public static function excluir(){
        // esta função ira remover a credencial e o seu respectivo tipo de usuario aluno ou adm do banco.
        // a ser implementado!.
    }
}
