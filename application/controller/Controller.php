<?php

namespace controller;

use model\administrador\Administrador;
use model\aluno\Aluno;
use model\credenciais\Credenciais;
use PDOException;

abstract class Controller
{

    public static function renderize(String $view, ?array $Model)
    {

        include VIEW . $view . '.php';
    }

    public static function logadoRedirect()
    {
        if (!isset($_SESSION["usuario"]) && !isset($_SESSION["senha"])) {
            header('location: /login');
            exit;
        }
    }
    public static function logado(): bool
    {

        if (isset($_SESSION["usuario"]) && isset($_SESSION["senha"]))
            return true;
        else
            return false;
    }

    public static function attDateTimeLogin(array $sessao): void
    {

        $obj = new Credenciais();
        $obj->id = (int)$sessao['id_credencial'];
        $obj->usuario = $sessao['usuario'];
        $obj->senha = $sessao['senha'];
        $obj->criado_em = $sessao['criado_em'];
        $dataHora = date('Y-m-d H:i:s');
        $obj->last_login = $dataHora;
        $obj->ativo = $sessao['ativo'];

        try {
            $obj->update();
        } catch (PDOException $e) {
            echo "Erro ao Atualizar banco de dados erro: " . $e->getMessage();
        }
    }
    public static function userOrAdm(int $id): array
    {

        $aluno = new Aluno();
        $aluno->id = $id;

        $adm = new Administrador();
        $adm->id = $aluno->id;

        if (is_object($aluno = $aluno->idCredConf())) {
            return [
                "type" => "aluno",
                "usuario" => $aluno
            ];
        } else {
            $adm = $adm->idCredConf();
            return [
                "type" => "administrador",
                "usuario" => $adm
            ];
        }
    }
}
