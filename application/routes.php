<?php

use controller\HomeController;
use controller\noticia\NoticiaController;
use controller\aviso\AvisoController;
use controller\evento\EventoController;
use controller\UsuarioController;
use controller\LoginController;


$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($uri) {

    case "/":
        HomeController::index();
        break;

    case "/login":
        LoginController::logar();
        break;

    case "/logout":
        LoginController::logout();
        break;
    case "/painel/noticia_evento_aviso":
        HomeController::paginaNoticiaEventoAviso();
        break;
    case "/painel/gerenciamento":
        HomeController::paginaGerenciamento();
        break;

    case "/cadastro/usuario":
        UsuarioController::cadastroUsuario();
        break;
    case "/atualizar/usuario":
        // chamado do controler para atualizar usuario...
        break;
    case "/excluir/usuario":
        break;


   


    case "/noticias":
        NoticiaController::get();
        break;
    case "/painel/noticia":
        NoticiaController::painelNoticia();
        break;
    case "/cadastro/noticia":
        NoticiaController::insert();
        break;
    case "/atualizar/noticia":
        NoticiaController::update();
        break;
    case "/excluir/noticia":
        NoticiaController::delete();
        break;

    //View eventos
   
    // case "/eventos":
    //     include VIEW . "evento/Evento.php";
    //     break;

    case "/painel/evento":
        EventoController::painelEvento();
        break;
    case "/cadastro/evento":
        EventoController::insert();
        break;
    case "/atualizar/evento":
        EventoController::update();
        break;
    case "/excluir/evento":
        EventoController::delete();
        break;

    //View avisos
    case "/avisos":
        include VIEW . "comunicado/Comunicados.php";
        break;
    case "/painel/aviso":
        AvisoController::painelAviso();
        break;
    case "/cadastro/aviso":
        AvisoController::insert();
        break;
    case "/atualizar/aviso":
        AvisoController::update();
        break;
    case "/excluir/aviso":
        AvisoController::delete();
        break;


    case "/contato":
        include VIEW . "contato/Contato.php";
        break;
    case "/perfil":
        UsuarioController::perfil();
        break;

    default:
        header('Location: /');
        exit;
}
