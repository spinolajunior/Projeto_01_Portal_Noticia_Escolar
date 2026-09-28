<?php

namespace controller;

use DateTimeImmutable;
use model\administrador\Administrador;
use model\aluno\Aluno;
use model\credenciais\Credenciais;
use PDOException;

abstract class Controller
{


    

    public static function attDateTimeLogin(int $id): void
    {

        $obj = new Credenciais();
        $obj->id = $id;

        try {
            $obj->updateLastLogin();
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

    public static function formatarData(string $data , string $format): string{
         return $obj = new DateTimeImmutable($data)->format($format);
    }
}
