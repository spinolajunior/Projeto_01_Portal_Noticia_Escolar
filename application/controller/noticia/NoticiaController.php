<?php

namespace controller\noticia;

use controller\Controller;
use controller\LoginController;
use controller\administrador\AdministradorController;
use model\Administrador\Administrador;
use model\Noticia\Noticia;
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
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $model = new Noticia();
            $model->titulo = $_POST["titulo"];
            $model->subtitulo = $_POST["subtitulo"];
            $model->descricao = $_POST["descricao"];
            $model->data_pub = date('Y-m-d H:i:s');
            $model->status = true;
            $model->id_administrador = $_SESSION["id_usuario"];

            $result = $model->insert();

            if ($result !== false) {

                $idNoticia = $result->id;

                $extensao = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
                $nomeImg = $idNoticia . "." . $extensao;
                $destino = "view/img/upload/" . $nomeImg;

                if (move_uploaded_file($_FILES['img']['tmp_name'], $destino)) {

                    $model = new Noticia();
                    $model->imagem = View::$uploadImagemNoticia . $idNoticia;
                    $model->id = $idNoticia;

                    if ($model->setImg() !== false) {
                        header('Location: /noticia?id=' . $idNoticia);
                        exit;
                    }
                }
            }
        }
        new View("Cadastrar Noticia", View::$nav_footer, View::$formCreate . "Cadastrar_noticia.php", null)->renderizar();
    }

    public static function get(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["id"])) {

            $model = new Noticia();
            $model->id = (int)$_GET["id"];
            $model =  $model->get();


            if ($model !== false) {

                $adm = new Administrador();
                $adm->id = $model->id_administrador;
                $adm = $adm->get();

                new View(
                    $model->titulo,
                    View::$nav_footer,
                    View::$dinamico . "noticia_id.php",
                    [
                        "noticia" => $model,
                        "adm" => $adm
                    ]
                )->renderizar();
            } else {

                new View(
                    '404 PAGINA NÃO ENCONTRADA!',
                    View::$nav_footer,
                    View::$estatico . "error_404.php",
                    null
                )->renderizar();
            }
        } else {
            header("Location: /");
            exit;
        }
    }

    public static function update(): void
    {

        LoginController::logadoRedirect("");
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


            if ($obj->update()) {

                // aqui faz a atualização da imagem da noticia no banco, caso o usuario mande uma imagem.
                if ($_FILES["imagem"]["size"] !== 0) {

                    $extensao = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);
                    $nomeImg = $obj->id . "." . $extensao;
                    $destino = "view/img/upload/" . $nomeImg;
                    unlink($destino);

                    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {

                        $model = new Noticia();
                        $model->imagem = View::$uploadImagemNoticia . $nomeImg;
                        $model->id = $obj->$id;

                        if ($model->setImg() !== false) {
                            header('Location: /painel/noticia?cod=331');

                            exit;
                        }
                    }
                }
                header("location: /painel/noticia?cod=332");
            } else {
                header('Location: /painel/noticia?cod=332');
            }
        }
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id']))
            $id = $_GET['id'];
        $obj = new Noticia();
        $obj->id = $id;
        $obj->delete();
        header('location: /painel/noticia');
        exit;
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
