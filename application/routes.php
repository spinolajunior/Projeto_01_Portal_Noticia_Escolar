<?php

use controller\HomeController;
use controller\noticia\NoticiaController;
use controller\aviso\AvisoController;
use controller\Controller;
use controller\evento\EventoController;
use controller\LogController;
use controller\UsuarioController;
use controller\LoginController;
use view\Flash_msg;


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'];
$somentePost = ['/logout', '/excluir/noticia', '/excluir/evento', '/excluir/aviso', '/excluir/usuario'];
$formularios = ['/login', '/cadastro/usuario', '/atualizar/usuario/aluno', '/atualizar/usuario/administrador', '/painel/acesso/usuario', '/atualizar/myself', '/cadastro/noticia', '/atualizar/noticia', '/cadastro/evento', '/atualizar/evento', '/cadastro/aviso', '/atualizar/aviso'];
$permitidos = in_array($uri, $somentePost, true) ? ['POST'] : (in_array($uri, $formularios, true) ? ['GET', 'POST'] : ['GET']);
if (!in_array($metodo, $permitidos, true)) {
    header('Allow: ' . implode(', ', $permitidos));
    HomeController::erro(405, 'Método de requisição não permitido.');
    return;
}
if ($metodo === 'POST') {
    Controller::validarCsrf();
}

switch ($uri) {

    case "/":
        HomeController::index();
        break;
    case "/contato":
        HomeController::emDesenvolvimento();
        break;
    case "/cetidam":
         HomeController::emDesenvolvimento();
         break;

    case "/not_found":
        HomeController::not_found();
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
    case "/painel/logs":
        LogController::painelLog();
        break;

    case "/cadastro/usuario":
        UsuarioController::cadastroUsuario();
        break;
    case "/atualizar/usuario/aluno":
        UsuarioController::editarAluno();
        break;
    case "/atualizar/usuario/administrador":
        UsuarioController::editarAdm();
        break;
    case "/painel/usuarios":
        UsuarioController::gerenciarUsuario();
        break;
    case "/excluir/usuario":
        UsuarioController::excluir();
        break;
    case "/painel/acesso/usuario":
        UsuarioController::updateAcessoUsuario();
        break;


    case "/atualizar/myself":
        UsuarioController::editarPerfil();
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
        HomeController::index();
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


    case "/perfil":
        UsuarioController::perfil();
        break;

    default:
        HomeController::not_found();
        break;
}
