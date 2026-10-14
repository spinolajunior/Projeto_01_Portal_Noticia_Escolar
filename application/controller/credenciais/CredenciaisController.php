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

    }
    public static function getAll(): array
    {
        $data = new Credenciais()->getAll();
        return $data;
    }

    public static function update() {}

    public static function delete() {}

    public static function setFoto(Credenciais $obj){
        if($obj->setFoto()){
            header('location: /perfil?cod=01');
            exit;
        }else{
            header('location: /perfil?cod=02');
        }
    }

    public static function cadastrar(Credenciais $obj): Credenciais | bool{
        return $obj->insert();
    }

    
}
