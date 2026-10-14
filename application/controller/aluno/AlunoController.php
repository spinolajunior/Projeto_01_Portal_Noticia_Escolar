<?php

namespace controller\aluno;

use controller\Controller;
use controller\LoginController;
use DAOs\aluno\AlunoDAO;
use view\View;



abstract class AlunoController extends Controller
{


    public static function get(): void
    {
        $alunoDAO = new AlunoDAO()->get();
    }

    public static function getById(): void
    {
        LoginController::logadoRedirect(null);

        new View("Perfil", View::$nav_footer, VIEW . "include/Perfil_aluno.php", null)->renderizar();
    }


    public static function insert(): void {

        LoginController::logadoRedirect(null);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
        new View("CADASTRO ALUNO",View::$nav_footer,VIEW."include/forms/create/cadastrar_aluno.php",null)->renderizar();
        }else{
            http_response_code(405);
            header("Location: /login");
            exit;
        }
    }

    public static function update(): void {
        LoginController::logadoRedirect(null);
        new View("ATUALIZAR ALUNO!",View::$nav_footer,VIEW."include/forms/update/update_adm.php",null)->renderizar();
    }

    public static function delete(): void {}
}
