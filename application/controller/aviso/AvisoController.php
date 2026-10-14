<?php

namespace controller\aviso;

use controller\Controller;
use controller\LoginController;
use model\aviso\Aviso;
use model\Escola\Escola;
use view\Flash_msg;
use view\View;

abstract class AvisoController extends Controller
{



    public static function getAll(): array{
        return new Aviso()->getAll();
    }



    public static function painelAviso()
    {
        LoginController::logadoRedirect("/painel/aviso");
        $obj = new Aviso()->getAll();
        $model = ["avisos" => array_reverse($obj)];
        new View("Painel Gerenciamento Aviso!", View::$nav_footer, View::$dinamicoPaineis . "painel_aviso.php", $model)->renderizar();
    }

    public static function insert()
    {
        LoginController::logadoRedirect("/cadastro/aviso");
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            new View("Cadastro Aviso!", View::$nav_footer, View::$formCreate . "cadastrar_aviso.php", null)->renderizar();
        }if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $id_usuario = $_SESSION["id_usuario"];
            $obj = new Aviso();
            $obj->titulo = $_POST['titulo'];
            $obj->ativo = '1';

            if(!isset($_SESSION['id_tipo_adm'])){
                $obj->id_aluno_adm = $id_usuario;
            }else{
                $obj->id_aluno_adm = $id_usuario;
            }

            if($obj->insert()){
                new Flash_msg(201)->flashMsg();
                header('location: /painel/aviso');
                exit;
            }else{
                new Flash_msg(501)->flashMsg();
                header('location: /painel/aviso');
                exit;
            }
        }
    }

    public static function update()
    {
        LoginController::logadoRedirect("/atualizar/aviso");
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $obj = new Aviso();
            $obj->id = $_GET['id'];
            $obj = $obj->get();
            if ($obj) {
                $model = ["aviso" => $obj];
                new View("Atualizar Aviso!", View::$nav_footer, View::$formEdit . "edit_aviso.php", $model)->renderizar();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            var_dump($_POST);
            $obj = new Aviso();
            $obj->id = $_POST['id'];
            $obj->titulo = $_POST['titulo'];
            $obj->ativo = $_POST['ativo'];
            if ($obj->update()) {
                header('location: /painel/aviso');
                exit;
            }
        }
    }
    public static function delete()
    {

        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["id"])) {
            $obj = new Aviso();
            $obj->id = (int)$_GET["id"];

            if ($obj->delete()) {
                header("location: /painel/aviso");
                exit;
            }
        }
    }
}
