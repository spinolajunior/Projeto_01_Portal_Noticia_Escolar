<?php

namespace controller\aluno;

use controller\Controller;
use DAOs\aluno\AlunoDAO;
use view\View;



abstract class AlunoController extends Controller
{


    public static function get(): void
    {
        $alunoDAO = new AlunoDAO()->get();
    }

    public static function getById(): void
    {
        self::logadoRedirect();

        new View("Perfil", View::$logado, VIEW . "include/Perfil_aluno.php", null)->renderizar();
    }


    public static function insert(): void {}

    public static function update(): void {}

    public static function delete(): void {}
}
