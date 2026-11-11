<?php

namespace controller;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use model\administrador\Administrador;
use model\aluno\Aluno;
use model\credenciais\Credenciais;
use controller\serie\SerieController;
use controller\tipo_adm\TipoAdmController;
use view\Flash_msg;
use finfo;

abstract class Controller
{
    public static function attDateTimeLogin(int $id): void
    {
        $obj = new Credenciais();
        $obj->id = $id;
        $obj->updateLastLogin();
    }

    public static function userOrAdm(int $id): array|false
    {
        $aluno = new Aluno();
        $aluno->id = $id;
        $aluno = $aluno->idCredConf();
        $adm = new Administrador();
        $adm->id = $id;
        $adm = $adm->idCredConf();

        if (($aluno === false) === ($adm === false)) {
            return false;
        }
        return ['type' => $aluno !== false ? 'aluno' : 'administrador', 'usuario' => $aluno !== false ? $aluno : $adm];
    }

    public static function formatarData(string $data, string $format): string
    {
        return new DateTimeImmutable($data)->format($format);
    }

    public static function verificarAcesso(object $sessao, int $nivel): void
    {
        if (!isset($sessao->id_credencial) || (int)($sessao->acesso ?? -1) < $nivel) {
            HomeController::erro(403, 'Você não possui permissão para realizar esta operação.');
            exit;
        }
    }

    public static function verificarGestor(): void
    {
        LoginController::logadoRedirect($_SERVER['REQUEST_URI'] ?? '/');
        self::verificarAcesso((object)$_SESSION, 1);
        if (($_SESSION['tipo_usuario'] ?? '') !== 'administrador') {
            HomeController::erro(403, 'Você não possui permissão para acessar a área administrativa.');
            exit;
        }
    }

    public static function nivelUsuario(object $usuario, string $tipo): int
    {
        if ($tipo === 'aluno' && $usuario->id_tipo_adm === null) {
            return 0;
        }
        static $tipos = [];
        $id = (int)($usuario->id_tipo_adm ?? 0);
        if (!array_key_exists($id, $tipos)) {
            $tipos[$id] = TipoAdmController::adm_acesso($id);
        }
        $nivel = $tipos[$id] !== false ? (int)$tipos[$id]['acesso'] : -1;
        return $nivel >= 1 && $nivel <= ($tipo === 'aluno' ? 1 : 4) ? $nivel : -1;
    }

    public static function podeGerenciarUsuario(object $usuario, string $tipo): bool
    {
        if (($_SESSION['tipo_usuario'] ?? '') !== 'administrador'
            || (int)$usuario->id_credenciais === (int)($_SESSION['id_credencial'] ?? 0)) {
            return false;
        }
        $nivel = self::nivelUsuario($usuario, $tipo);
        return $nivel >= 0 && $nivel < 4 && $nivel < (int)($_SESSION['acesso'] ?? 0);
    }

    public static function podeGerenciarPublicacao(object $publicacao): bool
    {
        $tipo = $publicacao->id_administrador !== null ? 'administrador' : 'aluno';
        $id = $tipo === 'administrador' ? $publicacao->id_administrador : $publicacao->id_aluno_adm;
        if ($id === null || (int)($_SESSION['acesso'] ?? 0) < 1) {
            return false;
        }
        if ($tipo === ($_SESSION['tipo_usuario'] ?? '') && (int)$id === (int)($_SESSION['id_usuario'] ?? 0)) {
            return true;
        }
        if (($_SESSION['tipo_usuario'] ?? '') !== 'administrador') {
            return false;
        }
        if ((int)$_SESSION['acesso'] === 4) {
            return true;
        }
        static $autores = [];
        $chave = $tipo . ':' . $id;
        if (!array_key_exists($chave, $autores)) {
            $autor = $tipo === 'administrador' ? new Administrador() : new Aluno();
            $autor->id = (int)$id;
            $autores[$chave] = $autor->get();
        }
        $autor = $autores[$chave];
        if ($autor === false) {
            return false;
        }
        $nivel = self::nivelUsuario($autor, $tipo);
        return $nivel >= 0 && ($nivel < (int)$_SESSION['acesso'] || ($tipo === 'aluno' && (int)$_SESSION['acesso'] === 1 && $nivel === 1));
    }

    public static function autorizarPublicacao(object|false $publicacao): void
    {
        if ($publicacao === false) {
            HomeController::not_found();
            exit;
        }
        if (!self::podeGerenciarPublicacao($publicacao)) {
            HomeController::erro(403, 'Você não possui permissão para gerenciar esta publicação.');
            exit;
        }
    }

    public static function campoPost(string $campo, int $minimo, int $maximo, bool $trim = true): string
    {
        $rotulos = [
            'titulo' => 'Título', 'subtitulo' => 'Subtítulo', 'descricao' => 'Conteúdo da notícia',
            'nome' => 'Nome completo', 'matricula' => 'Matrícula', 'usuario' => 'Nome de usuário',
            'senha' => 'Senha', 'confirmar_senha' => 'Confirmação da senha', 'data_evento' => 'Data do evento',
            'data_nascimento' => 'Data de nascimento', 'id_serie' => 'Série', 'tipo_usuario' => 'Tipo de conta',
            'status' => 'Situação da notícia', 'ativo' => 'Situação de acesso',
        ];
        $rotulo = $rotulos[$campo] ?? str_replace('_', ' ', $campo);
        $valor = $_POST[$campo] ?? '';
        if (!is_string($valor) || !mb_check_encoding($valor, 'UTF-8')) {
            throw new InvalidArgumentException('Não conseguimos ler o campo “' . $rotulo . '”. Preencha esse campo novamente para continuar.');
        }
        $valor = $trim ? trim($valor) : $valor;
        if (str_contains($valor, "\0")) {
            throw new InvalidArgumentException('O campo “' . $rotulo . '” contém um caractere que não conseguimos aceitar. Digite o valor novamente.');
        }
        $tamanho = mb_strlen($valor, 'UTF-8');
        if ($tamanho < $minimo) {
            throw new InvalidArgumentException($valor === '' ? 'Preencha o campo “' . $rotulo . '” para continuar.' : 'O campo “' . $rotulo . '” precisa ter pelo menos ' . $minimo . ' caracteres. Acrescente um pouco mais de informação.');
        }
        if ($tamanho > $maximo) {
            throw new InvalidArgumentException('O campo “' . $rotulo . '” aceita até ' . $maximo . ' caracteres. Reduza o texto e tente novamente.');
        }
        return $valor;
    }

    public static function idRequisicao(string $campo = 'id'): int
    {
        $origem = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' ? $_POST : $_GET;
        $valor = $origem[$campo] ?? null;
        if (!is_string($valor) || !ctype_digit($valor) || strlen($valor) > 10 || (int)$valor < 1 || (int)$valor > 2147483647) {
            throw new InvalidArgumentException('Não conseguimos identificar o registro escolhido. Volte à listagem e selecione o item novamente.');
        }
        return (int)$valor;
    }

    public static function validarData(string $data, bool $futura): void
    {
        $obj = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
        if (!$obj || $obj->format('Y-m-d') !== $data || $data < '1900-01-01'
            || ($futura ? $data < date('Y-m-d') : $data > date('Y-m-d'))) {
            throw new InvalidArgumentException($futura ? 'Confira a data do evento: escolha uma data válida a partir de hoje.' : 'Confira a data de nascimento: ela precisa ser uma data válida e não pode estar no futuro.');
        }
    }

    public static function validarCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? null;
        if (!is_string($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            LogController::registrar('csrf_recusado', 'falha');
            HomeController::erro(403, 'A sessão do formulário expirou. Reabra a página e tente novamente.');
            exit;
        }
    }

    public static function removerFoto(?string $url): void
    {
        if ($url === null || !preg_match('~^/view/img/upload/(?:perfil_usuario/)?[0-9]+_[a-f0-9]{16}\.jpg$~D', $url)) {
            return;
        }
        $arquivo = realpath(BASE_DIR . ltrim($url, '/'));
        $raiz = realpath(BASE_DIR . 'view/img/upload');
        if ($arquivo !== false && $raiz !== false && str_starts_with($arquivo, $raiz . DIRECTORY_SEPARATOR) && is_file($arquivo)) {
            if (!unlink($arquivo)) {
                LogController::registrar('limpeza_imagem', 'falha', 'arquivo', null, 'Não foi possível remover uma imagem antiga.');
            }
        }
    }

    public static function adicionarFoto(array $file,  string $diretorio, string $nomeImg, ?string $finalidade = null): string
    {
        //esse if verifica se uma ouve algum erro se o arquivo é maior que 10mb e se o upload foi valido vindo de um post;
        // caso alguma dessas expressoes retorne true será disparado uma exeção!;
        if (($file['error'] ?? null) !== UPLOAD_ERR_OK ||
            !is_int($file['size'] ?? null) || $file['size'] > 10 * 1024 * 1024 ||
            !is_string($file['tmp_name'] ?? null) || !is_uploaded_file($file['tmp_name'])
        ) {
            throw new InvalidArgumentException('Upload inválido. Selecione uma imagem de até 10 MB.');
        }
        // esta variavel armazena se o arquivo enviado é realmente uma imagem ou um arquivo malicioso;
        $mime  = new finfo(FILEINFO_MIME_TYPE)->file($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new InvalidArgumentException('Arquivo inválido. Use JPG, PNG ou WebP.');
        }

        // verifica os megapixels de uma imagem antes de transformala em bit a bit para verificar ...
        $arquivo = @getimagesize($file['tmp_name']);
        if (!$arquivo || $arquivo[0] * $arquivo[1] > 16000000) {
            throw new InvalidArgumentException('Imagem inválida: limite de 16 megapixels.');
        }

        // aqui vai poderia ter uma validação de um antivirus instalado no computador posso usar a função exec... para tal




        if ($finalidade === 'perfil') {
            $orientaca = ($mime === 'image/jpeg' && function_exists('exif_read_data')) ? @exif_read_data($file['tmp_name']) : [];
            $orientaca = $orientaca['Orientation'] ?? 1;
        }



        // aqui temos outro filtro para nossa imagem , transformamos ela em uma string de bit que em sequecia sera lida pelo php
        // que ira verificar se os bits formam uma imagem pura ... valida ou não.
        $arquivo = @imagecreatefromstring(file_get_contents($file['tmp_name']));
        if (!$arquivo) {
            throw new InvalidArgumentException('Imagem inválida ou corrompida.');
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

        $escala = min(1, ($finalidade === 'perfil' ? 1024 : 1920) / max($arquivo_x, $arquivo_y));
        $largura = max(1, (int)round($arquivo_x * $escala));
        $altura = max(1, (int)round($arquivo_y * $escala));
        $img = imagecreatetruecolor($largura, $altura);
        $branco = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $branco);
        imagecopyresampled($img, $arquivo, 0, 0, 0, 0, $largura, $altura, $arquivo_x, $arquivo_y);


        if (!ctype_digit($nomeImg) || !is_dir($diretorio)) {
            throw new Exception("Diretorio invalido!");
        }

        $nome = $nomeImg . '_' . bin2hex(random_bytes(8)) . '.jpg';
        $local_save = rtrim($diretorio, '/\\') . '/' . $nome;

        if (!imagejpeg($img, $local_save, 80)) {
            if (is_file($local_save)) { unlink($local_save); }
            throw new Exception('Falha ao salvar imagem!');
        }

        unset($arquivo, $img);
        return ($finalidade === 'perfil')?"/view/img/upload/perfil_usuario/" . $nome:"/view/img/upload/". $nome;
    }


    public static function atualizarSession(bool $validarCredencial = true): bool
    {
        $credencial = new Credenciais();
        $credencial->id = (int)($_SESSION['id_credencial'] ?? 0);
        $credencial = $credencial->get();
        if ($credencial === false || !$credencial->ativo) {
            return false;
        }
        $marca = hash_hmac('sha256', $credencial->senha, session_id());
        if ($validarCredencial && (!isset($_SESSION['auth_stamp']) || !hash_equals($_SESSION['auth_stamp'], $marca))) {
            return false;
        }
        $perfil = self::userOrAdm($credencial->id);
        if ($perfil === false || ($nivel = self::nivelUsuario($perfil['usuario'], $perfil['type'])) < 0) {
            return false;
        }
        $usuario = $perfil['usuario'];
        foreach (['senha', 'id_tipo_adm', 'cargo', 'data_nascimento', 'serie', 'id_serie'] as $campo) {
            unset($_SESSION[$campo]);
        }
        foreach (['usuario', 'criado_em', 'ativo', 'foto', 'last_login'] as $campo) {
            $_SESSION[$campo] = $credencial->$campo;
        }
        $_SESSION['auth_stamp'] = $marca;
        $_SESSION['tipo_usuario'] = $perfil['type'];
        $_SESSION['id_usuario'] = $usuario->id;
        $_SESSION['nome'] = $usuario->nome;
        $_SESSION['matricula'] = $usuario->matricula;
        $_SESSION['acesso'] = $nivel;
        if ($perfil['type'] === 'aluno') {
            $_SESSION['data_nascimento'] = $usuario->data_nascimento;
            $_SESSION['id_serie'] = $usuario->id_serie;
            $_SESSION['serie'] = SerieController::serieById((int)$usuario->id_serie) ?: '';
        } else {
            $_SESSION['id_tipo_adm'] = $usuario->id_tipo_adm;
            $_SESSION['cargo'] = TipoAdmController::adm_acesso($usuario->id_tipo_adm)['cargo'];
        }
        return true;
    }
}
