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

abstract class UsuarioController extends Controller
{

    public static function perfil()
    {
        LoginController::logadoRedirect("/perfil");
        $model = ['usuario' => (object)$_SESSION];
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
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($session->id_tipo_adm)) {
            echo ("post here! <br>");
            var_dump($_POST);

            $post = (object)$_POST;
            $usuario = new Administrador();
            $usuario->id = $post->id;
            $usuario->nome = $post->nome;
            $usuario->matricula = $post->matricula;
            $usuario = $usuario->update();

            $credencial = new Credenciais();
            $credencial->id = $post->id_credenciais;
            $credencial->usuario = $post->usuario;
            $credencial->senha = $post->senha;
            $credencial = $credencial->update();

            if ($usuario !== false && $credencial !== false) {
                new Flash_msg(202)->flashMsg();
                header('location: /atualizar/myself');
            } else {
                new Flash_msg(501)->flashMsg();
                header('location: /atualizar/myself');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($session->id_tipo_adm)) {
            var_dump($_POST);
            $post = (object)$_POST;
            $usuario = new Aluno();
            $usuario->id = $post->id;
            $usuario->data_nascimento = $post->data_nascimento;
            $usuario->id_serie = $post->id_serie;
            $usuario->nome = $post->nome;
            $usuario->matricula = $post->matricula;
            $usuario = $usuario->update();

            $credencial = new Credenciais();
            $credencial->id = $post->id_credencial;
            $credencial->usuario = $post->usuario;
            $credencial->senha = $post->senha;
            $credencial = $credencial->update();
            
            if($credencial !== false && $credencial !== false){
                new Flash_msg(202,"Usuario $post->nome Atualizado!")->flashMsg();
                header('location: /atualizar/myself');
                exit;
            }else{
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
            var_dump($_POST);
            echo ("<br><br>");
            $post = (object)$_POST;
            $obj =  new Aluno();
            $obj->id = $post->id;
            $obj->nome = $post->nome;
            $obj->matricula = $post->matricula;
            $obj->data_nascimento = $post->data_nascimento;
            $obj->id_serie = $post->id_serie;



            $obj_ = new Credenciais();
            $obj_->usuario = $post->usuario;
            $obj_->senha = $post->senha;
            $obj_->id = $post->id_credencial;


            if ($obj->update() && $obj_->update()) {
                new Flash_msg(202)->flashMsg();
                header("location: /painel/usuarios");
                exit;
            } else {
                new Flash_msg(501)->flashMsg();
                header("location: /painel/usuarios");
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
            $consulta = $usuario->update();
            if ($consulta) {
                echo ("Sucesso ao atualizar o adm!");
            } else {
                echo ("falha ao atualizar o adm");
            }

            $credencial = new Credenciais();
            $credencial->id = $post->id_credenciais;
            $credencial->usuario = $post->usuario;
            $credencial->senha = $post->senha;
            $consulta = $credencial->update();
            if ($consulta) {
                echo ("<h1>Sucesso ao atualizar credencial</h1>");
            } else {
                echo ("<h1>falha ao atualizar credencial</h1>");
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
        echo("<h1>POST HERE!</h1>");
        $post = (object)$_POST;
        var_dump($post);
        $credencial = new Credenciais();
        $credencial->id = $post->id_credencial;
        // preciso mudar o DAO para ele permitir a liberação ou o bloqueio...
        
        }
    }
}
