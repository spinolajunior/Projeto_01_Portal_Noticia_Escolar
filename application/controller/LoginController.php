<?php

namespace controller;

use view\View;
use model\credenciais\Credenciais;
use controller\serie\SerieController;
use controller\tipo_adm\TipoAdmController;

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
                    $_SESSION['id_credencial'] = $usuario->id;
                    $_SESSION['criado_em'] = $usuario->criado_em;
                    $_SESSION['ativo'] = $usuario->ativo;
                    $_SESSION['foto'] = $usuario->foto;
                    $_SESSION['senha'] = $usuario->senha;
                    $_SESSION['last_login'] = $usuario->last_login;

                    $usuario = self::userOrAdm((int)$_SESSION["id_credencial"]);

                    if ($usuario["type"] == "aluno") {
                        $usuario = $usuario["usuario"];
                        $_SESSION['data_nascimento'] = $usuario->data_nascimento;
                        $_SESSION['serie'] = SerieController::serieById((int)$usuario->id_serie);
                        $_SESSION['id_serie'] = $usuario->id_serie;
                    } else {
                        $usuario = $usuario["usuario"];
                        $adm_acesso = TipoAdmController::adm_acesso($usuario->id_tipo_adm);
                        $_SESSION['id_tipo_adm'] = $usuario->id_tipo_adm;
                        $_SESSION['id_tipo_adm'] = $usuario->id_tipo_adm;
                        $_SESSION['cargo'] = $adm_acesso["cargo"];
                        $_SESSION['acesso'] = $adm_acesso["acesso"];
                    }
                    $_SESSION['id_usuario'] = $usuario->id;
                    $_SESSION['nome'] =  $usuario->nome;
                    $_SESSION['matricula'] = $usuario->matricula;


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
                    $model = [
                        "error" => "404",
                        "msg" => "Credenciais Invalidas!"
                    ];
                    new View("Login", View::$nav_footer, View::$formPost . "form_login.php", $model)->renderizar();
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

            new View("Login", View::$nav_footer, View::$formPost . "form_login.php", null)->renderizar();
        }
    }

    public static function logout(): void
    {
        session_destroy();
        header('location: /');
        exit;
    }
}
