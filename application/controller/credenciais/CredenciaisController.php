<?php

namespace controller\credenciais;

use controller\Controller;
use controller\UsuarioController;
use model\administrador\Administrador;
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
        self::verificarGestor();
        self::verificarAcesso((object)$_SESSION, 4);
        $data = new Credenciais()->getAll();
        foreach ($data as $credencial) { unset($credencial->senha); }
        return $data;
    }

    public static function update() {}

    public static function delete() {}

    public static function setFoto(Credenciais $obj){
        // Compatibilidade: usa o fluxo de perfil que já valida upload e autoria.
        UsuarioController::editarPerfil();
    }

    public static function cadastrar(Credenciais $obj): Credenciais | bool{
        // Credencial sem perfil gera conta órfã; o cadastro passa pelo fluxo transacional.
        UsuarioController::cadastroUsuario();
        return false;
    }

    
}
