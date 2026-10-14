<?php

namespace controller\evento;

use controller\LoginController;
use controller\Controller;
use view\Flash_msg;
use view\View;
use model\evento\Evento;

abstract class EventoController extends Controller
{

    public static function painelEvento(): void
    {
        LoginController::logadoRedirect("/painel/evento");
        $obj = new Evento()->get();
        $model = ["eventos" => $obj];
        new View("Painel Gerenciamento Evento!", VIEW::$nav_footer, VIEW::$dinamicoPaineis . "painel_evento.php", $model)->renderizar();
    }

    public static function insert()
    {
        LoginController::logadoRedirect("/cadastro/evento");
        LoginController::verificarAcesso((object)$_SESSION,1);
        
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            new View("Cadastrar Evento!", VIEW::$nav_footer, VIEW::$formCreate . "cadastrar_evento.php", null)->renderizar();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $obj = new Evento();
            $obj->titulo = $_POST["titulo"];
            $obj->data_evento = $_POST["data_evento"];

            if (!isset($_SESSION['id_tipo_adm'])) {
                $obj->id_aluno_adm = (int)$_SESSION['id_usuario'];
            } else {
                $obj->id_administrador = (int)$_SESSION["id_usuario"];
            }

            if ($obj->insert()) {
                new Flash_msg(201)->flashMsg();
                header("location: /painel/evento");
                exit;
            }else{
                new Flash_msg(501)->flashMsg();
                header("location: /painel/evento");
                exit;
            }
        }
    }
    public static function update()
    {
        LoginController::logadoRedirect("/atualizar/evento");
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["id"])) {
            $obj = new Evento();
            $obj->id = (int)$_GET["id"];
            $obj = $obj->getById();
            $model = ["evento" => $obj];
            new View("Atualizar Evento!", VIEW::$nav_footer, VIEW::$formEdit . "edit_evento.php", $model)->renderizar();
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
            var_dump($_POST);
            $obj = new Evento();
            $obj->id = $_POST['id'];
            $obj->titulo = $_POST['titulo'];
            $obj->data_evento = $_POST['data_evento'];
            if ($obj->update()) {
                header('location: /painel/evento');
                exit;
            }
        }
    }
    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["id"])) {
            $obj = new Evento();
            $obj->id = (int)$_GET["id"];

            if ($obj->delete()) {
                header("location: /painel/evento");
                exit;
            }
        }
    }
    public static function get() {}
    public static function getAll(): array
    {
        $consulta = new Evento()->get();
        return ($consulta != false) ? $consulta : [];
    }
    public static function getById() {}
}
