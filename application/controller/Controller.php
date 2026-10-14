<?php

namespace controller;

use DateTimeImmutable;
use Exception;
use model\administrador\Administrador;
use model\aluno\Aluno;
use model\credenciais\Credenciais;
use controller\serie\SerieController;
use controller\tipo_Adm\TipoAdmController;
use PDOException;
use view\Flash_msg;
use finfo;

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

    public static function formatarData(string $data, string $format): string
    {
        return new DateTimeImmutable($data)->format($format);
    }

    public static function verificarAcesso(object $sessao, int $nivel)
    {

        if (isset($sessao->acesso) && $sessao->acesso >= $nivel) {
        } else {

            new Flash_msg(403)->flashMsg();
            header('location: /');
            exit;
        }
    }

    public static function adicionarFoto(array $file,  string $diretorio, string $nomeImg, ?string $finalidade = null): string
    {
        //esse if verifica se uma ouve algum erro se o arquivo é maior que 10mb e se o upload foi valido vindo de um post;
        // caso alguma dessas expressoes retorne true será disparado uma exeção!;
        if (($file['error'] ?? null) !== UPLOAD_ERR_OK ||
            $file['size'] > 10 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])
        ) {
            throw new Exception('Upload Invalido!');
        }
        // esta variavel armazena se o arquivo enviado é realmente uma imagem ou um arquivo malicioso;
        $mime  = new finfo(FILEINFO_MIME_TYPE)->file($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new Exception('Arquivo invalido!');
        }

        // verifica os megapixels de uma imagem antes de transformala em bit a bit para verificar ...
        $arquivo = @getimagesize($file['tmp_name']);
        if (!$arquivo || $arquivo[0] * $arquivo[1] > 16000000) {
            throw new Exception('Dimensões em Mega pixels invalida maximo 16Mp!');
        }

        // aqui vai poderia ter uma validação de um antivirus instalado no computador posso usar a função exec... para tal
        



        if ($finalidade === 'perfil') {
            $orientaca = @exif_read_data($file['tmp_name']);
            $orientaca = $orientaca['Orientation'] ?? 1;
        }



        // aqui temos outro filtro para nossa imagem , transformamos ela em uma string de bit que em sequecia sera lida pelo php
        // que ira verificar se os bits formam uma imagem pura ... valida ou não.
        $arquivo = @imagecreatefromstring(file_get_contents($file['tmp_name']));
        if (!$arquivo) {
            throw new Exception('Arquivo não reconhecido / invalido!');
        }
        if ($finalidade === 'perfil') {
            switch ($orientaca) {

                case 3:
                    $arquivo = imagerotate($arquivo, 180, 0);
                    break;
                case 6:
                    $arquivo = imagerotate($arquivo, 270, 0);
                    break;
                case 8:
                    $arquivo = imagerotate($arquivo, 90, 0);
                    break;
            }
        }

        $arquivo_x = imagesx($arquivo);
        $arquivo_y = imagesy($arquivo);

        $img =  imagecreatetruecolor($arquivo_x, $arquivo_y);
        $branco = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $branco);
        imagecopy($img, $arquivo, 0, 0, 0, 0, $arquivo_x, $arquivo_y);


        if (!is_dir($diretorio)) {
            throw new Exception("Diretorio invalido!");
        }

        $nome = $nomeImg . '_' . bin2hex(random_bytes(8)) . '.jpg';
        $local_save = rtrim($diretorio, '/\\') . '/' . $nome;

        if (!imagejpeg($img, $local_save, 80)) {
            throw new Exception('Falha ao salvar imagem!');
        }

        unset($arquivo, $img);
        return ($finalidade === 'perfil')?"/view/img/upload/perfil_usuario/" . $nome:"/view/img/upload/". $nome;
    }

    public static function atualizarSession(){
         

                $usuario = new Credenciais();
                $usuario -> id = $_SESSION['id_credencial'];
                $usuario = $usuario -> get();
                if ($usuario !==  false ) {
                    $_SESSION['usuario'] = $usuario->usuario;
                    $_SESSION['id_credencial'] = $usuario->id;
                    $_SESSION['criado_em'] = $usuario->criado_em;
                    $_SESSION['ativo'] = $usuario->ativo;
                    $_SESSION['foto'] = $usuario->foto;
                    $_SESSION['senha'] = $usuario->senha;
                    $_SESSION['last_login'] = $usuario->last_login;

                    $usuario = self::userOrAdm((int)$_SESSION["id_credencial"]);

                    if ($usuario["type"] == "aluno") {
                        $usuario = $usuario["usuario"];
                        $_SESSION['data_nascimento'] = $usuario->data_nascimento;
                        $_SESSION['serie'] = SerieController::serieById((int)$usuario->id_serie);
                        $_SESSION['id_serie'] = $usuario->id_serie;

                        if ($usuario->id_tipo_adm !== null) {
                            $nivel_acesso = TipoAdmController::aluno_nivel_acesso($usuario->id_tipo_adm);
                            if ($nivel_acesso !== false) {
                                $_SESSION['acesso'] = $nivel_acesso;
                            }
                        }
                    } else {
                        $usuario = $usuario["usuario"];
                        $adm_acesso = TipoAdmController::adm_acesso($usuario->id_tipo_adm);
                        $_SESSION['id_tipo_adm'] = $usuario->id_tipo_adm;
                        $_SESSION['cargo'] = $adm_acesso["cargo"];
                        $_SESSION['acesso'] = $adm_acesso["acesso"];
                    }

                    $_SESSION['id_usuario'] = $usuario->id;
                    $_SESSION['nome'] =  $usuario->nome;
                    $_SESSION['matricula'] = $usuario->matricula;

            }
    }

}
