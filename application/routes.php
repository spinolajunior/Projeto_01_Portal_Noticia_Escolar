<?php

use controller\credenciais\CredenciaisController;
use controller\HomeController;
use controller\noticia\NoticiaController;
use controller\aluno\AlunoController;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($uri) {


    case "/":
        HomeController::index();
        break;

    case "/login":
        CredenciaisController::logar();
        break;

    case "/logout":
        CredenciaisController::logout();
        break;

    case "/noticias":
        NoticiaController::get();
        break;
    case "/noticia/cadastro":
        NoticiaController::insert();
        break;

    case "/noticia/edit":
        NoticiaController::insert();
        break;

    case "/eventos":
        include VIEW . "evento/Evento.php";
        break;

    case "/evento/cadastro":
        include VIEW . "evento/Evento.php";
        break;
    case "/evento/edit":
        include VIEW . "evento/Evento.php";
        break;

    case "/avisos":
        include VIEW . "comunicado/Comunicados.php";
        break;
    case "/aviso/cadastro":
        include VIEW . "comunicado/Comunicados.php";
        break;
    case "/aviso/edit":
        include VIEW . "comunicado/Comunicados.php";
        break;

    case "/contato":
        include VIEW . "contato/Contato.php";
        break;

    case "/perfil/aluno":
        AlunoController::getById();
        break;
    case "/perfil/administrador":
        include VIEW . "perfil/Perfil.php";
        break;
    default:
        header('Location: /');
        exit;
}
