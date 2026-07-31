<?php
namespace controller\credenciais;
use model\credenciais\Credenciais;

abstract class CredenciaisController{

    public static function getAll():array{
        $data =new Credenciais()->getAll(); 
        var_dump($data);
        return $data;
    }

}