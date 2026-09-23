<?php

namespace controller\credenciais;

use controller\Controller;
use model\Administrador\Administrador;
use model\credenciais\Credenciais;
use model\aluno\Aluno;
use view\View;

abstract class CredenciaisController extends Controller
{

    public static function get()
    {
        $data = new Credenciais()->get();
        $model["obj"] = $data;
        self::renderize("view", $model);
    }
    public static function getAll(): array
    {
        $data = new Credenciais()->getAll();
        return $data;
    }

    public static function update() {}

    public static function delete() {}

    
}
