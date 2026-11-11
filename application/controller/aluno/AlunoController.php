<?php

namespace controller\aluno;

use controller\Controller;
use controller\LoginController;
use controller\UsuarioController;
use DAOs\aluno\AlunoDAO;
use view\View;



abstract class AlunoController extends Controller
{


    public static function get(): void
    {
        UsuarioController::gerenciarUsuario();
    }

    public static function getById(): void
    {
        UsuarioController::perfil();
    }


    public static function insert(): void {

        UsuarioController::cadastroUsuario();
    }

    public static function update(): void {
        UsuarioController::editarAluno();
    }

    public static function delete(): void { UsuarioController::excluir(); }
}
