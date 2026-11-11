<?php
// Somente leitura. Não imprime senhas, hashes nem dados pessoais.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/application/bootstrap.php';
try {
    $pdo = new PDO($_ENV['db']['dsn'], $_ENV['db']['user'], $_ENV['db']['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $schema = ['versao' => $pdo->query('SELECT VERSION()')->fetchColumn(), 'tabelas' => []];
    foreach ($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $tabela) {
        $nome = '`' . str_replace('`', '``', $tabela) . '`';
        $schema['tabelas'][$tabela] = [
            'ddl' => $pdo->query('SHOW CREATE TABLE ' . $nome)->fetch(PDO::FETCH_NUM)[1],
            'registros' => (int)$pdo->query('SELECT COUNT(*) FROM ' . $nome)->fetchColumn(),
        ];
    }
    $schema['perfis'] = $pdo->query('SELECT * FROM tipo_adm ORDER BY nivel_acesso')->fetchAll(PDO::FETCH_ASSOC);
    $schema['series'] = $pdo->query('SELECT * FROM serie')->fetchAll(PDO::FETCH_ASSOC);
    $schema['integridade'] = [
        'administradores_nivel_4' => (int)$pdo->query('SELECT COUNT(*) FROM administrador a JOIN tipo_adm t ON t.id=a.id_tipo_adm WHERE t.nivel_acesso=4')->fetchColumn(),
        'tipos_fora_do_intervalo' => (int)$pdo->query('SELECT COUNT(*) FROM tipo_adm WHERE nivel_acesso NOT BETWEEN 1 AND 4')->fetchColumn(),
        'credenciais_sem_perfil' => (int)$pdo->query('SELECT COUNT(*) FROM credenciais c LEFT JOIN aluno a ON a.id_credenciais=c.id LEFT JOIN administrador d ON d.id_credenciais=c.id WHERE a.id IS NULL AND d.id IS NULL')->fetchColumn(),
        'credenciais_com_dois_perfis' => (int)$pdo->query('SELECT COUNT(*) FROM credenciais c JOIN aluno a ON a.id_credenciais=c.id JOIN administrador d ON d.id_credenciais=c.id')->fetchColumn(),
        'senhas_legadas_sha256' => (int)$pdo->query("SELECT COUNT(*) FROM credenciais WHERE senha REGEXP '^[a-fA-F0-9]{64}$'")->fetchColumn(),
    ];
    foreach (['noticia', 'evento', 'aviso'] as $tabela) {
        $schema['integridade'][$tabela . '_autoria_inconsistente'] = (int)$pdo->query("SELECT COUNT(*) FROM $tabela WHERE (id_administrador IS NULL AND id_aluno_adm IS NULL) OR (id_administrador IS NOT NULL AND id_aluno_adm IS NOT NULL)")->fetchColumn();
    }
    echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    fwrite(STDERR, 'Não foi possível inspecionar o banco (código ' . $e->getCode() . ').' . PHP_EOL);
    exit(1);
}
