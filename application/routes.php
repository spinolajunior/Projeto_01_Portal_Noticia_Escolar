<?php

use controller\HomeController;
use controller\noticia\NoticiaController;
use controller\aluno\AlunoController;
use controller\administrador\AdministradorController;
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

    case "/cadastro/aluno":
        AlunoController::insert();
        break;
    case "/atualizar/aluno":
        AlunoController::update();
        break;
    case "/excluir/aluno":
        break;


    case "/cadastro/administrador":
        AdministradorController::insert();
        break;
    case "/atualizar/administrador":
        AdministradorController::update();
        break;
    case "/excluir/administrador":
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
        NoticiaController::insert();
        break;
    case "/excluir/noticia":
        break;

    //View eventos
    case "/eventos":
        include VIEW . "evento/Evento.php";
        break;
    case "/painel/evento":
        EventoController::painelEvento();
        break;
    case "/cadastro/evento":
        include VIEW . "evento/Evento.php";
        break;
    case "/atualizar/evento":
        include VIEW . "evento/Evento.php";
        break;
    case "/excluir/evento":
        break;

    //View avisos
    case "/avisos":
        include VIEW . "comunicado/Comunicados.php";
        break;
    case "/painel/aviso":
        AvisoController::painelAviso();
        break;
    case "/aviso/cadastro":
        include VIEW . "comunicado/Comunicados.php";
        break;
    case "/atualizar/aviso":
        include VIEW . "comunicado/Comunicados.php";
        break;
    case "/exlcuir/aviso":
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
