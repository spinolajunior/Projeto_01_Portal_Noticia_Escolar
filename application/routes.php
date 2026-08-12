<?php

use controller\Controller;
use controller\credenciais\CredenciaisController;
use controller\HomeController;
use controller\LoginController;
use controller\noticia\NoticiaController;

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

    case "/noticia":
        NoticiaController::get();
        break;
    case "/noticia/cadastro":
        NoticiaController::insert();
        break;

    case "/eventos":
        include VIEW . "evento/Eventos.php";
        break;

    case "/evento":
        include VIEW . "evento/Evento.php";
        break;

    case "/comunicados":
        include VIEW . "comunicado/Comunicados.php";
        break;

    case "/comunicado":
        include VIEW . "comunicado/Comunicado.php";
        break;

    case "/contato":
        include VIEW . "contato/Contato.php";
        break;

    case "/perfil":
        include VIEW . "perfil/Perfil.php";
        break;
    default:
        header('Location: /');
        exit;
}
