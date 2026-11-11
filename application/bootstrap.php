<?php
// Cada instalação mantém suas credenciais em config.php, fora do Git.
require __DIR__ . '/config.php';
date_default_timezone_set('America/Bahia');

foreach (['host' => 'localhost', 'port' => '3306', 'dbname' => 'portal_CETIDAM', 'user' => '', 'pass' => ''] as $campo => $padrao) {
    // Permite que os testes usem uma base isolada sem editar o config.php local.
    $valor = getenv('CETIDAM_DB_' . strtoupper($campo === 'dbname' ? 'name' : $campo));
    $_ENV['db'][$campo] = $valor !== false ? $valor : ($_ENV['db'][$campo] ?? $padrao);
}
// Compatibilidade com o host:porta utilizado na configuração original.
if (preg_match('/^([^:]+):(\d+)$/', $_ENV['db']['host'], $partes)) {
    $_ENV['db']['host'] = $partes[1];
    $_ENV['db']['port'] = $partes[2];
}
$_ENV['db']['dsn'] = 'mysql:host=' . $_ENV['db']['host'] . ';port=' . $_ENV['db']['port']
    . ';dbname=' . $_ENV['db']['dbname'] . ';charset=utf8mb4';

// O config.php antigo já definia BASE_DIR e VIEW; seus valores são preservados.
if (!defined('BASE_DIR')) { define('BASE_DIR', __DIR__ . '/'); }
if (!defined('VIEW')) { define('VIEW', BASE_DIR . 'view/'); }
define('PRIVATE_DIR', getenv('CETIDAM_STORAGE_DIR') ?: dirname(__DIR__) . '/storage');
define('SESSION_TIMEOUT', max(60, (int)(getenv('CETIDAM_SESSION_TIMEOUT') ?: 1800)));
