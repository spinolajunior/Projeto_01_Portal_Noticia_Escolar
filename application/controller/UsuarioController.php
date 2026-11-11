<?php

namespace controller;

use model\administrador\Administrador;
use model\aluno\Aluno;
use model\serie\Serie;
use controller\serie\SerieController;
use model\tipo_adm\Tipo_adm;
use controller\tipo_adm\TipoAdmController;
use model\credenciais\Credenciais;
use model\credenciais\CredenciaisCadastroAdministrador;
use model\credenciais\CredenciaisCadastroAluno;
use model\noticia\Noticia;
use view\View;
use view\Flash_msg;
use InvalidArgumentException;
use Throwable;

abstract class UsuarioController extends Controller
{
    public static function perfil(): void
    {
        LoginController::logadoRedirect('/perfil');
        new View('Perfil', View::$nav_footer, View::$dinamico . ($_SESSION['tipo_usuario'] === 'aluno' ? 'perfil_aluno.php' : 'perfil_administrador.php'), ['session' => (object)$_SESSION])->renderizar();
    }

    public static function editarPerfil(): void
    {
        LoginController::logadoRedirect('/atualizar/myself');
        if ($_SESSION['tipo_usuario'] !== 'administrador') {
            new Flash_msg(403)->flashMsg();
            header('Location: /perfil');
            exit;
        }
        self::editarUsuario('administrador', true);
    }

    public static function editarAluno(): void
    {
        self::verificarGestor();
        self::editarUsuario('aluno', false);
    }

    public static function editarAdm(): void
    {
        self::verificarGestor();
        self::editarUsuario('administrador', false);
    }

    // Os três fluxos de edição usam a mesma validação e a mesma limpeza de fotos.
    private static function editarUsuario(string $tipo, bool $proprio): void
    {
        $usuario = $tipo === 'aluno' ? new Aluno() : new Administrador();
        $usuario->id = $proprio ? (int)$_SESSION['id_usuario'] : self::idRequisicao();
        $usuario = $usuario->get();
        if ($usuario === false) {
            HomeController::not_found();
            return;
        }
        if (!$proprio && !self::podeGerenciarUsuario($usuario, $tipo)) {
            HomeController::erro(403, 'Você não possui permissão para gerenciar esta conta.');
            return;
        }
        $credencial = new Credenciais();
        $credencial->id = (int)$usuario->id_credenciais;
        $credencial = $credencial->get();
        if ($credencial === false) {
            HomeController::not_found();
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $model = ['usuario' => $usuario, 'aluno' => $usuario, 'credencial' => $credencial, 'credenciais' => $credencial, 'series' => SerieController::get() ?: []];
            $arquivo = $proprio ? 'edit_myself_adm.php' : ($tipo === 'aluno' ? 'edit_aluno.php' : 'edit_administrador.php');
            new View('Editar usuário', View::$nav_footer, View::$formEdit . $arquivo, $model)->renderizar();
            return;
        }
        $dados = self::validarDadosUsuario(false, $tipo === 'aluno', $credencial->usuario);
        $usuario->nome = $dados['nome'];
        $usuario->matricula = $dados['matricula'];
        if ($tipo === 'aluno') {
            $usuario->data_nascimento = $dados['data_nascimento'];
            $usuario->id_serie = $dados['id_serie'];
        }
        $fotoAnterior = $credencial->foto;
        $credencial->usuario = $dados['usuario'];
        $credencial->senha = $dados['senha'];
        $fotoNova = null;
        try {
            if (isset($_FILES['foto']) && ($_FILES['foto']['error'] ?? null) !== UPLOAD_ERR_NO_FILE) {
                $fotoNova = self::adicionarFoto($_FILES['foto'], BASE_DIR . 'view/img/upload/perfil_usuario', (string)$credencial->id, 'perfil');
            }
            $usuario->array = ['usuario' => $usuario, 'credencial' => $credencial, 'foto_update' => $fotoNova];
            if (!$usuario->atualizarDados()) {
                throw new \RuntimeException('Não foi possível atualizar a conta.');
            }
        } catch (Throwable $e) {
            self::removerFoto($fotoNova);
            throw $e;
        }
        if ($fotoNova !== null) {
            self::removerFoto($fotoAnterior);
        }
        if ($proprio) {
            self::atualizarSession(false);
        }
        LogController::registrar('usuario_alterado', 'sucesso', $tipo, $usuario->id);
        new Flash_msg(202)->flashMsg();
        header('Location: ' . ($proprio ? '/atualizar/myself' : '/painel/usuarios'));
        exit;
    }

    private static function validarDadosUsuario(bool $cadastro, bool $aluno, string $usuarioAtual = ''): array
    {
        $nome = self::campoPost('nome', 3, 100);
        $matricula = self::campoPost('matricula', 0, 100);
        $usuario = self::campoPost('usuario', $cadastro ? 3 : 0, 100);
        $senha = self::campoPost('senha', $cadastro ? 8 : 0, 72, false);
        $confirmacao = self::campoPost('confirmar_senha', 0, 72, false);
        if (!preg_match('/^[\p{L}\p{M} ]+$/uD', $nome)) {
            throw new InvalidArgumentException('No campo “Nome completo”, use somente letras, acentos e espaços. Confira o nome e tente novamente.');
        }
        if ($matricula !== '' && !ctype_digit($matricula)) {
            throw new InvalidArgumentException('A matrícula precisa conter apenas números. Retire letras, espaços e símbolos antes de continuar.');
        }
        if ($usuario !== '' && !preg_match('/^[a-zA-Z0-9]{3,100}$/D', $usuario)) {
            throw new InvalidArgumentException('Escolha um nome de usuário com 3 a 100 letras ou números, sem espaços, acentos ou símbolos.');
        }
        if ($senha !== '') {
            if (mb_strlen($senha) < 8) {
                throw new InvalidArgumentException('Sua senha precisa ter pelo menos 8 caracteres. Acrescente mais caracteres para proteger a conta.');
            }
            if (strlen($senha) > 72) {
                throw new InvalidArgumentException('Essa senha ficou longa demais. Reduza a quantidade de caracteres e tente novamente.');
            }
            if ($senha === ($usuario !== '' ? $usuario : $usuarioAtual)) {
                throw new InvalidArgumentException('Para proteger a conta, escolha uma senha diferente do nome de usuário.');
            }
        }
        if ($senha !== $confirmacao) {
            throw new InvalidArgumentException('As duas senhas ficaram diferentes. Digite a mesma senha nos campos “Senha” e “Confirmação da senha” para continuar.');
        }
        $dados = ['nome' => $nome, 'matricula' => $matricula !== '' ? $matricula : null, 'usuario' => $usuario, 'senha' => $senha];
        if ($aluno) {
            $dados['data_nascimento'] = self::campoPost('data_nascimento', 10, 10);
            self::validarData($dados['data_nascimento'], false);
            $dados['id_serie'] = self::idRequisicao('id_serie');
            $serie = new Serie();
            $serie->id = $dados['id_serie'];
            if (!$serie->getById()) {
                throw new InvalidArgumentException('Escolha uma das séries disponíveis na lista. Se a série desejada não aparecer, procure a administração do portal.');
            }
        }
        return $dados;
    }

    public static function cadastroUsuario(): void
    {
        self::verificarGestor();
        // IDs dos cargos institucionais existentes no esquema; tipo técnico ? reservado ao banco.
        $tipos = array_values(array_filter(new Tipo_adm()->getAll(), static fn($tipo) => in_array($tipo->id, [1, 2, 3], true) && $tipo->nivel_acesso === $tipo->id && $tipo->nivel_acesso < (int)$_SESSION['acesso']));
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Cadastro de usuário', View::$nav_footer, View::$formCreate . 'cadastrar_usuario.php', ['series' => SerieController::get() ?: [], 'tipos' => $tipos])->renderizar();
            return;
        }
        $tipo = self::campoPost('tipo_usuario', 1, 10);
        if ($tipo !== '0' && !in_array($tipo, array_map(static fn($tipo) => (string)$tipo->id, $tipos), true)) {
            HomeController::erro(403, 'Você não possui permissão para cadastrar este tipo de conta.');
            return;
        }
        $dados = self::validarDadosUsuario(true, $tipo === '0');
        if ($tipo === '0') {
            $novo = new CredenciaisCadastroAluno($dados['usuario'], $dados['senha'], $dados['nome'], $dados['matricula'], $dados['data_nascimento'], $dados['id_serie']);
            $ok = $novo->cadastrarAluno();
        } else {
            $novo = new CredenciaisCadastroAdministrador($dados['usuario'], $dados['senha'], $dados['nome'], $dados['matricula'], (int)$tipo);
            $ok = $novo->cadastrarAdm();
        }
        LogController::registrar('usuario_cadastrado', $ok ? 'sucesso' : 'falha', $tipo === '0' ? 'aluno' : 'administrador');
        new Flash_msg($ok ? 201 : 409)->flashMsg();
        header('Location: /cadastro/usuario');
        exit;
    }

    private static function listasUsuarios(): array
    {
        $alunos = array_values(array_filter(new Aluno()->alunoStatus() ?: [], static fn($aluno) => self::podeGerenciarUsuario($aluno, 'aluno')));
        $adm = array_values(array_filter(new Administrador()->admStatus() ?: [], static fn($adm) => self::podeGerenciarUsuario($adm, 'administrador')));
        foreach ($alunos as $aluno) {
            $aluno->nome_serie = SerieController::serieById((int)$aluno->id_serie) ?: '';
        }
        foreach ($adm as $usuario) {
            $usuario->cargo = TipoAdmController::adm_acesso((int)$usuario->id_tipo_adm)['cargo'];
        }
        return ['alunos' => $alunos, 'adm' => $adm, 'administradores' => $adm];
    }

    public static function gerenciarUsuario(): void
    {
        self::verificarGestor();
        new View('Gerenciar usuários', View::$nav_footer, View::$dinamicoPaineis . 'painel_usuarios.php', self::listasUsuarios())->renderizar();
    }

    public static function updateAcessoUsuario(): void
    {
        self::verificarGestor();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Acesso dos usuários', View::$nav_footer, View::$dinamicoPaineis . 'painel_acesso_usuario.php', self::listasUsuarios())->renderizar();
            return;
        }
        $id = self::idRequisicao('id_credenciais');
        $alvo = self::userOrAdm($id);
        if ($alvo === false || !self::podeGerenciarUsuario($alvo['usuario'], $alvo['type'])) {
            HomeController::erro(403, 'Você não possui permissão para alterar o acesso desta conta.');
            return;
        }
        $ativo = self::campoPost('ativo', 1, 1);
        if (!in_array($ativo, ['0', '1'], true)) {
            throw new InvalidArgumentException('Status de acesso inválido.');
        }
        $credencial = new Credenciais();
        $credencial->id = $id;
        $credencial->ativo = $ativo === '1';
        $ok = $credencial->acesso();
        LogController::registrar($ativo === '1' ? 'usuario_reativado' : 'usuario_bloqueado', $ok ? 'sucesso' : 'falha', 'credenciais', $id);
        new Flash_msg($ok ? 207 : 501)->flashMsg();
        header('Location: /painel/acesso/usuario');
        exit;
    }

    public static function excluir(): void
    {
        self::verificarGestor();
        $tipo = self::campoPost('tipo_usuario', 1, 20);
        if (!in_array($tipo, ['aluno', 'administrador'], true)) {
            throw new InvalidArgumentException('Tipo de usuário inválido.');
        }
        $usuario = $tipo === 'aluno' ? new Aluno() : new Administrador();
        $usuario->id = self::idRequisicao();
        $usuario = $usuario->get();
        if ($usuario === false) {
            HomeController::not_found();
            return;
        }
        if (!self::podeGerenciarUsuario($usuario, $tipo)) {
            HomeController::erro(403, 'Você não possui permissão para excluir esta conta.');
            return;
        }
        $credencial = new Credenciais();
        $credencial->id = (int)$usuario->id_credenciais;
        $credencial = $credencial->get();
        $imagens = [$credencial->foto];
        foreach (new Noticia()->getAll() as $noticia) {
            if (($tipo === 'aluno' ? $noticia->id_aluno_adm : $noticia->id_administrador) === $usuario->id) {
                $imagens[] = $noticia->imagem;
            }
        }
        $ok = $credencial->delete();
        if ($ok) {
            foreach ($imagens as $imagem) {
                self::removerFoto($imagem);
            }
        }
        LogController::registrar('usuario_excluido', $ok ? 'sucesso' : 'falha', $tipo, $usuario->id);
        new Flash_msg($ok ? 203 : 501)->flashMsg();
        header('Location: /painel/usuarios');
        exit;
    }
}
