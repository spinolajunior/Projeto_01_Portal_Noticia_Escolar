<?php

namespace controller;

use view\View;
use model\credenciais\Credenciais;

abstract class LoginController extends Controller
{
    public static function logadoRedirect(?string $redirect)
    {
        if (!isset($_SESSION["usuario"]) && !isset($_SESSION["senha"])) {
            header('location: /login' . ($redirect != null ? '?next_url=' . $redirect : ''));
            exit;
        }
    }
    public static function logado(): bool
    {

        if (isset($_SESSION["usuario"]) && isset($_SESSION["senha"]))
            return true;
        else
            return false;
    }

    public static function logar()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['usuario']) && isset($_POST['senha'])) {

                $usuario = new Credenciais();
                $usuario->usuario = $_POST['usuario'];
                $usuario->senha = $_POST['senha'];

                if (is_object($usuario = $usuario->logar())) {
                    $_SESSION['usuario'] = $usuario->usuario;
                    $_SESSION['senha'] = $usuario->senha;
                    $_SESSION['id_credencial'] = $usuario->id;
                    $_SESSION['criado_em'] = $usuario->criado_em;
                    $_SESSION['last_login'] = $usuario->last_login;
                    $_SESSION['ativo'] = $usuario->ativo;
                    $usuario = self::userOrAdm((int)$_SESSION["id_credencial"]);

                    if ($usuario["type"] == "aluno") {
                        $usuario = $usuario["usuario"];
                        $_SESSION['data_nascimento'] = $usuario->data_nascimento;
                    } else {
                        $usuario = $usuario["usuario"];
                        $_SESSION['id_tipo_adm'] = $usuario->id_tipo_adm;
                        $_SESSION['id_contato'] = $usuario->id_contato;
                        $_SESSION['id_endereco'] = $usuario->id_endereco;
                        $_SESSION['cpf'] = $usuario->cpf;
                    }
                    $_SESSION['id_usuario'] = $usuario->id;
                    $_SESSION['nome'] =  $usuario->nome;
                    $_SESSION['matricula'] = $usuario->matricula;
                    self::attDateTimeLogin($_SESSION);

                    if (isset($_POST['checkLembrar'])) {

                        setcookie('usuario', $_POST['usuario'], time() + 60 * 60 * 24 * 30);
                        setcookie('senha', $_POST['senha'], time() + 60 * 60 * 24 * 30);
                    }

                    if (isset($_GET['next_url'])) {
                        header('location: ' . $_GET['next_url']);
                        exit;
                    } else {
                         header('location: /');
                        exit;
                    }
                } else {
                    new View("Login", VIEW . "template/nav_footer_logado.php", VIEW . "include/forms/form_login.php", null)->renderizar();
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

            new View("Login", View::$nav_footer, View::$formPost."form_login.php", null)->renderizar();
        }
    }

    public static function logout(): void
    {
        session_destroy();
        header('location: /');
        exit;
    }
}
