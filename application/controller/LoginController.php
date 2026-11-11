<?php

namespace controller;

use model\credenciais\Credenciais;
use view\Flash_msg;
use view\View;

abstract class LoginController extends Controller
{
    public static function urlInterna(mixed $url): string
    {
        if (!is_string($url) || strlen($url) > 2048) {
            return '/';
        }
        $decodificada = $url;
        for ($i = 0; $i < 3; $i++) {
            $decodificada = rawurldecode($decodificada);
        }
        return str_starts_with($decodificada, '/') && !str_starts_with($decodificada, '//')
            && !preg_match('/[\x00-\x20\x7f\\\\]/', $decodificada) ? $url : '/';
    }

    public static function logadoRedirect(?string $redirect): void
    {
        if (!self::logado()) {
            header('Location: /login?next_url=' . rawurlencode(self::urlInterna($redirect)));
            exit;
        }
    }

    public static function logado(): bool
    {
        return isset($_SESSION['id_credencial']) && ($_SESSION['ativo'] ?? false) === true;
    }

    public static function logar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            new View('Login', View::$nav_footer, View::$formPost . 'form_login.php', null)->renderizar();
            return;
        }
        $credencial = new Credenciais();
        $credencial->usuario = self::campoPost('usuario', 1, 100);
        $credencial->senha = self::campoPost('senha', 1, 4096, false);
        $usuario = $credencial->logar();
        if ($usuario === false || !$usuario->ativo) {
            LogController::registrar('login', 'falha', 'credenciais', $usuario !== false ? $usuario->id : null);
            new Flash_msg($usuario !== false ? 423 : 401)->flashMsg();
            header('Location: /login');
            exit;
        }
        // Substitui integralmente a sessão anterior para não carregar permissões antigas.
        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['id_credencial'] = $usuario->id;
        if (!self::atualizarSession(false)) {
            $_SESSION = [];
            new Flash_msg(403)->flashMsg();
            header('Location: /login');
            exit;
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['ultima_atividade'] = time();
        LogController::registrar('login', 'sucesso', 'credenciais', $usuario->id);
        header('Location: ' . self::urlInterna($_GET['next_url'] ?? '/'));
        exit;
    }

    public static function logout(): void
    {
        self::logadoRedirect('/perfil');
        LogController::registrar('logout', 'sucesso');
        $_SESSION = [];
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => $cookie['path'], 'secure' => $cookie['secure'], 'httponly' => true, 'samesite' => 'Lax']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}
