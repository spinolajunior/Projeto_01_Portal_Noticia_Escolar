<?php


ob_start();
try {
    require __DIR__ . '/bootstrap.php';
    require __DIR__ . '/autoload.php';
    date_default_timezone_set('America/Bahia');
    foreach (['logs', 'sessions'] as $pasta) {
        $diretorio = PRIVATE_DIR . '/' . $pasta;
        if (!is_dir($diretorio) && !mkdir($diretorio, 0700, true) && !is_dir($diretorio)) {
            throw new RuntimeException('Falha ao preparar armazenamento privado.');
        }
    }
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', PRIVATE_DIR . '/logs/php.log');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.gc_maxlifetime', (string)SESSION_TIMEOUT);
    session_save_path(PRIVATE_DIR . '/sessions');
    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
    $nonce = base64_encode(random_bytes(18));
    header_remove('X-Powered-By');
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce'; style-src-attr 'unsafe-inline'; img-src 'self' blob:; font-src 'self'; object-src 'none'; base-uri 'none'; frame-ancestors 'none'; form-action 'self'");
    header('Cache-Control: no-store');
    if ($https) {
        header('Strict-Transport-Security: max-age=31536000');
    }
    foreach (['usuario', 'senha'] as $legado) {
        if (isset($_COOKIE[$legado])) {
            setcookie($legado, '', ['expires' => time() - 3600, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
        }
    }
    if (isset($_SESSION['id_credencial'])) {
        if (time() - (int)($_SESSION['ultima_atividade'] ?? 0) > SESSION_TIMEOUT || !\controller\Controller::atualizarSession()) {
            $_SESSION = [];
            session_regenerate_id(true);
            new \view\Flash_msg(419)->flashMsg();
            header('Location: /login');
            exit;
        }
        $_SESSION['ultima_atividade'] = time();
    }
    $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
    require __DIR__ . '/routes.php';
    ob_end_flush();
} catch (Throwable $e) {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    $codigo = $e instanceof InvalidArgumentException ? 422 : ($e instanceof PDOException && (string)$e->getCode() === '23000' ? 409 : 500);
    error_log(get_class($e) . ' código ' . $e->getCode() . ' em ' . $e->getFile() . ':' . $e->getLine());
    if (class_exists(\controller\HomeController::class)) {
        \controller\LogController::registrar('operacao_recusada', 'erro', null, null, 'Código HTTP ' . $codigo);
        \controller\HomeController::erro($codigo, $codigo === 422 ? $e->getMessage() : ($codigo === 409 ? 'Já existe um registro com os dados informados.' : 'Não foi possível concluir a operação. Tente novamente.'));
    } else {
        http_response_code(500);
        echo 'Serviço temporariamente indisponível.';
    }
}
