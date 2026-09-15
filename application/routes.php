<?php

use controller\credenciais\CredenciaisController;
use controller\HomeController;
use controller\noticia\NoticiaController;
use controller\aluno\AlunoController;
use controller\administrador\AdministradorController;
use model\Administrador\Administrador;

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
    case "/cadastro/noticia":
        NoticiaController::insert();
        break;

    case "/atualizar/noticia":
        NoticiaController::insert();
        break;
    case "/excluir/noticia":
        break;


    case "/eventos":
        include VIEW . "evento/Evento.php";
        break;

    case "/cadastro/evento":
        include VIEW . "evento/Evento.php";
        break;
    case "/atualizar/evento":
        include VIEW . "evento/Evento.php";
        break;
    case "/excluir/evento":
        break;


    case "/avisos":
        include VIEW . "comunicado/Comunicados.php";
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
        // aqui vai a logica para o redirect ... do perfil do tipo adm ou aluno.
        break;

    case "/perfil/aluno":
        AlunoController::getById();
        break;

    case "/perfil/administrador":
        AdministradorController::getById();
        break;
        
    default:
        header('Location: /');
        exit;
}
