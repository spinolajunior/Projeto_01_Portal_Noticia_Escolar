<?php

namespace controller\noticia;

use controller\Controller;
use controller\LoginController;
use model\Administrador\Administrador;
use model\Aluno\Aluno;
use model\Noticia\Noticia;
use model\credenciais\Credenciais;
use Throwable;
use view\Flash_msg;
use view\View;


abstract class NoticiaController extends Controller
{
    public static function getALL(): bool|array
    {
        $model = new Noticia()->getAll();

        return count($model) > 0 ? $model : false;
    }

    public static function insert(): void
    {
        LoginController::logadoRedirect("/cadastro/noticia");
        LoginController::verificarAcesso((object)$_SESSION, 1);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $operacoes =  0;

            $model = new Noticia();
            $model->titulo = $_POST["titulo"];
            $model->subtitulo = $_POST["subtitulo"];
            $model->descricao = $_POST["descricao"];
            $model->data_pub = date('Y-m-d H:i:s');
            $model->status = true;
            if (!isset($_SESSION['id_tipo_adm'])) {
                $model->id_aluno_adm = (int)$_SESSION['id_usuario'];
            } else {
                $model->id_administrador = $_SESSION["id_usuario"];
            }
            $result = $model->insert();

            if ($result !== false) {

                $idNoticia = $result->id;

                try {

                    $model = new Noticia();
                    $model->id = $idNoticia;
                    $model->imagem = self::adicionarFoto($_FILES['img'], View::$uploadImagemNoticia, $idNoticia);

                    if ($model->setImg()) {
                        $operacoes++;
                    }
                } catch (Throwable $e) {
                    $result->delete();
                    new Flash_msg(501)->flashMsg();
                    header('location: /painel/noticia');
                    exit;
                }
            }
            new Flash_msg(200)->flashMsg();
            header('Location: /painel/noticia');
            exit;
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
            new View("Cadastrar Noticia", View::$nav_footer, View::$formCreate . "Cadastrar_noticia.php", null)->renderizar();
        }
    }

    public static function get(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["id"])) {

            $model = null;
            $user_resposavel = null;

            $model = new Noticia();
            $model->id = (int)$_GET["id"];
            $model =  $model->get();


            if ($model !== false && $model->id_administrador !== null) {

                $user_resposavel = new Administrador();
                $user_resposavel->id = $model->id_administrador;
                $user_resposavel = $user_resposavel->get();
                
                if($user_resposavel === false){
                    new Flash_msg(502)->flashMsg();
                    header('location: /');
                    exit;
                }
            } elseif ($model !== false && $model->id_aluno_adm !== null) {

                $user_resposavel =  new Aluno();
                $user_resposavel->id = $model->id_aluno_adm;
                $user_resposavel= $user_resposavel->get();
                
                if($user_resposavel === false){
                    new Flash_msg(502)->flashMsg();
                    header('location: /');
                    exit;
                }
            } elseif ($model === false) {

                new Flash_msg(404,"Noticia id= {$_GET['id']} não encontrada!")->flashMsg();
                header('location: /not_found');
                exit;
            }
            $foto = new Credenciais();
            $foto->id = $user_resposavel->id_credenciais;
            $foto = $foto->buscarFotoAutorPubli();


            new View(
                $model->titulo,
                View::$nav_footer,
                View::$dinamico . "noticia_id.php",
                [
                    "noticia" => $model,
                    "adm" => $user_resposavel,
                    "foto_autor" =>($foto !== false)?$foto : null
                ]
            )->renderizar();
        } else {
            new Flash_msg(510)->flashMsg();
            header("Location: /");
            exit;
        }
    }

    public static function update(): void
    {

        LoginController::logadoRedirect("");
        LoginController::verificarAcesso((object)$_SESSION, 1);
        $obj =  new Noticia();
        if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET["id"])) {
            $obj->id = (int)$_GET['id'];
            $consulta = $obj->get();
            $model = (is_object($consulta)) ? ["noticia" => $consulta, "url_imagem" => $consulta->imagem] : null;

            new View("Editar Noticia!", View::$nav_footer, View::$formEdit . "edit_noticia.php", $model)->renderizar();
        } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {

            $id = $_POST["id"];
            $titulo = $_POST["titulo"];
            $subtitulo = $_POST["subtitulo"];
            $descricao = $_POST["descricao"];
            $status = $_POST["status"];

            $obj = new Noticia();
            $obj->id = $id;
            $obj->titulo = $titulo;
            $obj->subtitulo = $subtitulo;
            $obj->descricao = $descricao;
            $obj->status = $status;




            try {
                $obj->update();
                if ($_FILES["imagem"]["size"] !== 0 && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    $idNoticia = $obj->id;
                    $model = new Noticia();
                    $model->id = $obj->id;
                    $model->imagem = self::adicionarFoto($_FILES['imagem'], View::$uploadImagemNoticia, $idNoticia);

                    $noticia = $model->get();
                    if ($model->setImg() && $noticia) {
                        unlink(preg_replace('~/+~', '/', BASE_DIR . $noticia->imagem));
                        new Flash_msg(200)->flashMsg();
                        header('location: /painel/noticia');
                        exit;
                    }
                } else {
                    new Flash_msg(200)->flashMsg();
                    header('location: /painel/noticia');
                    exit;
                }
            } catch (Throwable $e) {
                new Flash_msg(501)->flashMsg();
                header('location: /painel/noticia');
                exit;
            }
        }
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id']))
            $id = $_GET['id'];
        $obj = new Noticia();
        $obj->id = $id;
        $noticia = $obj->get();
        if ($obj->delete() && $noticia) {
            unlink(preg_replace('~/+~', '/', BASE_DIR . $noticia->imagem));
            new Flash_msg(200)->flashMsg();
            header('location: /painel/noticia');
            exit;
        } else {
            new Flash_msg(501)->flashMsg();
            header('location: /painel/noticia');
            exit;
        }
    }

    public static function painelNoticia()
    {
        LoginController::logadoRedirect("/painel/noticia");
        $consulta = new Noticia()->noticia_autor_all();
        $model = [
            "noticias" => array_reverse($consulta)
        ];
        new View("Painel Gerenciamento Notícia!", View::$nav_footer, View::$dinamicoPaineis . "painel_noticia.php", $model)->renderizar();
    }


}
