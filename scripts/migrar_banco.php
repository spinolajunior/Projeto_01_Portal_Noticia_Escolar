<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/application/bootstrap.php';
if (!in_array('--apply', $argv, true) && !in_array('--backup-only', $argv, true)) {
    echo "Uso: php scripts/migrar_banco.php --apply [--admin] ou --backup-only\nFaz backup privado antes de alterar o esquema.\n";
    exit;
}
$credenciais = $_ENV['db'];
if (in_array('--admin', $argv, true) && is_file(dirname(__DIR__) . '/config/maintenance.php')) {
    $credenciais = array_replace($credenciais, require dirname(__DIR__) . '/config/maintenance.php');
}
$pdo = new PDO($_ENV['db']['dsn'], $credenciais['user'], $credenciais['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$diretorio = getenv('CETIDAM_BACKUP_DIR') ?: (getenv('LOCALAPPDATA') ? getenv('LOCALAPPDATA') . '/PortalCETIDAM/backups' : dirname(__DIR__, 2) . '/portal_cetidam_backups');
if (!is_dir($diretorio) && !mkdir($diretorio, 0700, true) && !is_dir($diretorio)) {
    throw new RuntimeException('Não foi possível criar o diretório de backup.');
}
$backup = $diretorio . '/' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $_ENV['db']['dbname']) . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.sql';
$sql = "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n";
// Snapshot consistente dos dados InnoDB; nenhuma alteração de registros é feita.
$pdo->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
$pdo->beginTransaction();
foreach ($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $tabela) {
    $nome = '`' . str_replace('`', '``', $tabela) . '`';
    $sql .= 'DROP TABLE IF EXISTS ' . $nome . ";\n" . $pdo->query('SHOW CREATE TABLE ' . $nome)->fetch(PDO::FETCH_NUM)[1] . ";\n";
    foreach ($pdo->query('SELECT * FROM ' . $nome)->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $colunas = implode(',', array_map(static fn($coluna) => '`' . str_replace('`', '``', $coluna) . '`', array_keys($linha)));
        $valores = implode(',', array_map(static fn($valor) => $valor === null ? 'NULL' : $pdo->quote((string)$valor), array_values($linha)));
        $sql .= 'INSERT INTO ' . $nome . ' (' . $colunas . ') VALUES (' . $valores . ");\n";
    }
}
$pdo->commit();
$sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
if (file_put_contents($backup, $sql, LOCK_EX) === false) {
    throw new RuntimeException('Falha ao salvar backup; migração cancelada.');
}
chmod($backup, 0600);
echo 'Backup criado: ' . $backup . PHP_EOL;
if (in_array('--backup-only', $argv, true)) { exit; }
$indiceSenha = $pdo->query("SHOW INDEX FROM credenciais WHERE Key_name = 'senha'")->fetch();
if ($indiceSenha) {
    $pdo->exec('ALTER TABLE credenciais DROP INDEX senha');
    echo "Removida unicidade de senha.\n";
}
$pdo->exec('ALTER TABLE credenciais MODIFY senha VARCHAR(255) NOT NULL');
$pdo->exec('ALTER TABLE aviso MODIFY titulo VARCHAR(80) NOT NULL');
foreach (['aluno', 'administrador'] as $tabela) {
    $duplicadas = (int)$pdo->query("SELECT COUNT(*) FROM (SELECT matricula FROM $tabela WHERE matricula IS NOT NULL GROUP BY matricula HAVING COUNT(*) > 1) repetidas")->fetchColumn();
    if ($duplicadas > 0) {
        echo "$tabela: índice de matrícula pendente ($duplicadas grupos repetidos preexistentes).\n";
        continue;
    }
    $indice = $pdo->query("SHOW INDEX FROM $tabela WHERE Key_name = 'uq_{$tabela}_matricula'")->fetch();
    if (!$indice) {
        $pdo->exec("CREATE UNIQUE INDEX uq_{$tabela}_matricula ON $tabela (matricula)");
    }
    echo "$tabela: unicidade de matrícula verificada.\n";
}
$pdo->exec(file_get_contents(dirname(__DIR__) . '/database/migracoes/002_remover_escola.sql'));
echo "Migração concluída. Tabela escola removida; demais registros preservados.\n";
