<?php

namespace controller;

use model\Administrador\Administrador;
use model\Aluno\Aluno;
use view\View;
use controller\serie\SerieController;
use controller\credenciais\CredenciaisController;
use model\credenciais\Credenciais;

abstract class UsuarioController extends Controller
{

    public static function perfil()
    {
        LoginController::logadoRedirect("/perfil");
        $model = ['usuario' => (object)$_SESSION];
        if (!isset($_SESSION['id_tipo_adm'])) {
            new View("Perfil", View::$nav_footer, View::$dinamico . "perfil_aluno.php", $model)->renderizar();
        } else {
            var_dump($_SESSION);
            new View("Perfil", View::$nav_footer, View::$dinamico . "perfil_administrador.php", $model)->renderizar();
        }
    }

    public static function editarPerfil()
    {
        LoginController::logadoRedirect(null);
    }

    public static function cadastroUsuario()
    {
        LoginController::logadoRedirect("/cadastro/usuario");
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $model = ["series" => SerieController::get()];
            new View("Cadastro de Usuario!", View::$nav_footer, View::$formCreate . "cadastrar_usuario.php", $model)->renderizar();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            var_dump($_POST);
            $post = (object)$_POST;
            $credenciais = new Credenciais();
            $credenciais->usuario = $post->usuario;
            $credenciais->senha = $post->senha;
            $consulta = CredenciaisController::cadastrar($credenciais);
            if ($consulta) {
                if ((int)$post->tipo_usuario != 0 ) {
                    $adm = new Administrador();
                    $adm->nome = $post->nome;
                    $adm->matricula = $post->matricula;
                    $adm->id_tipo_adm = (int)$post->tipo_usuario;
                    $adm->id_credenciais = (int)$consulta->id;
                    $resultado = $adm->insert();
                    if ($resultado) {
                        echo "sucesso!";
                    }
                }else{
                    $aluno = new Aluno();
                    $aluno->nome = $post->nome;
                    $aluno->matricula = $post->matricula;
                    $aluno->data_nascimento = $post->data_nascimento;
                    $aluno->id_serie = $post->id_serie;
                    $aluno->id_credenciais = $consulta->id;
                    $resultado = $aluno->insert();
                    if($resultado){
                        echo("Sucesso!");
                    }
                }
            }
        }
    }
}
