<?php

namespace controller\noticia;

use controller\Controller;
use model\Administrador\Administrador;
use model\Noticia\Noticia;
use view\View;
use Exception;

abstract class NoticiaController extends Controller
{
    public static function getALL(): bool|array
    {
        $model = new Noticia()->getAll();

        return count($model) > 0 ? $model : false;
    }

    public static function insert(): void
    {
        self::logado();
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
                    $model->imagem = $idNoticia;
                    $model->id = $idNoticia;

                    if ($model->setImg() !== false) {
                        header('Location: /noticia?id=' . $idNoticia);
                        exit;
                    }
                }
            }
        }
        new View("Cadastrar Noticia", VIEW . "template/Nav_footer_out.php", VIEW . "include/forms/Cadastrar_noticia.php", null)->renderizar();
    }

    public static function get(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["id"])) {
            $model = new Noticia();
            $model->id = (int)$_GET["id"];
            $model =  $model->get();

            if (isset($_SESSION['usuario']) && isset($_SESSION['senha'])) {

                if ($model !== false) {
                    $adm = new Administrador();
                    $adm->id = $model->id_administrador;
                    $adm = $adm->get();

                    new View(
                        $model->titulo,
                        VIEW . "template/Nav_footer_out.php",
                        VIEW . "include/Noticia_id.php",
                        [
                            "noticia" => $model,
                            "adm" => $adm
                        ]
                    )->renderizar();
                } else {
                    header("location: /");
                    exit;
                }
            } else {
                new View($model->titulo, VIEW . "template/Nav_footer_logado.php", VIEW . "include/Noticia_id.php", ["noticia" => $model])->renderizar();
            }
        }
    }
}
