<?php
// Ferramenta de teste CLI: nunca atende requisições nem usa dados reais nos testes.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/application/bootstrap.php';
require dirname(__DIR__) . '/application/autoload.php';
date_default_timezone_set('America/Bahia');
$pasta = __DIR__ . '/.artifacts';
if (!is_dir($pasta)) { mkdir($pasta, 0700, true); }
$admin = is_file(dirname(__DIR__) . '/config/maintenance.php') ? require dirname(__DIR__) . '/config/maintenance.php' : $_ENV['db'];
$pdo = new PDO($_ENV['db']['dsn'], $admin['user'], $admin['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$acao = $argv[1] ?? 'setup';
if ($acao === 'runtime_grants') {
    $runtime = new PDO($_ENV['db']['dsn'], $_ENV['db']['user'], $_ENV['db']['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo json_encode($runtime->query('SHOW GRANTS')->fetchAll(PDO::FETCH_COLUMN));
    exit;
}
if ($acao === 'restore') {
    $candidatos = glob($pasta . '/storage/backups/*.sql');
    usort($candidatos, static fn($a, $b) => filemtime($b) <=> filemtime($a));
    if (!$candidatos) { throw new RuntimeException('Backup sintético não encontrado.'); }
    $nome = 'cetidam_restore_' . bin2hex(random_bytes(6));
    $pdo->exec('CREATE DATABASE `' . $nome . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_bin');
    try {
        $restore = new PDO('mysql:host=' . $_ENV['db']['host'] . ';port=' . $_ENV['db']['port'] . ';dbname=' . $nome . ';charset=utf8mb4', $admin['user'], $admin['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $restore->exec(file_get_contents($candidatos[0]));
        $contagens = [];
        foreach ($restore->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $tabela) {
            $contagens[$tabela] = (int)$restore->query('SELECT COUNT(*) FROM `' . $tabela . '`')->fetchColumn();
        }
        echo json_encode($contagens);
    } finally {
        $pdo->exec('DROP DATABASE `' . $nome . '`');
    }
    exit;
}
if ($acao === 'snapshot') {
    $estado = [];
    foreach ($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $tabela) {
        $linhas = $pdo->query('SELECT * FROM `' . str_replace('`', '``', $tabela) . '` ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
        $estado[$tabela] = ['quantidade' => count($linhas), 'hash' => hash('sha256', serialize($linhas))];
    }
    echo json_encode($estado, JSON_THROW_ON_ERROR);
    exit;
}
if ($acao === 'setup') {
    $nome = 'cetidam_test_' . bin2hex(random_bytes(6));
    $pdo->exec('CREATE DATABASE `' . $nome . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_bin');
    $meta = ['database' => $nome, 'password' => 'SenhaTeste123!', 'db_user' => $nome, 'db_pass' => bin2hex(random_bytes(24)), 'actors' => [], 'publications' => []];
    file_put_contents($pasta . '/fixture.json', json_encode($meta));
    $teste = new PDO('mysql:host=' . $_ENV['db']['host'] . ';port=' . $_ENV['db']['port'] . ';dbname=' . $nome . ';charset=utf8mb4', $admin['user'], $admin['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $teste->exec('SET FOREIGN_KEY_CHECKS=0');
    foreach ($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $tabela) {
        $ddl = $pdo->query('SHOW CREATE TABLE `' . str_replace('`', '``', $tabela) . '`')->fetch(PDO::FETCH_NUM)[1];
        $teste->exec(preg_replace('/AUTO_INCREMENT=\d+/', '', $ddl));
    }
    $teste->exec('SET FOREIGN_KEY_CHECKS=1');
    $pdo->exec('CREATE USER ' . $pdo->quote($meta['db_user']) . "@'localhost' IDENTIFIED BY " . $pdo->quote($meta['db_pass']));
    $pdo->exec('GRANT SELECT, INSERT, UPDATE, DELETE ON `' . $nome . '`.* TO ' . $pdo->quote($meta['db_user']) . "@'localhost'");
    foreach ([1 => 'Professor', 2 => 'Administração', 3 => 'Diretor Geral', 4 => 'ADM do Sistema'] as $id => $cargo) {
        $teste->prepare('INSERT INTO tipo_adm (id, cargo, nivel_acesso) VALUES (?, ?, ?)')->execute([$id, $cargo, $id]);
    }
    $teste->exec("INSERT INTO serie (id, nome) VALUES (1, 'Série de teste')");
    foreach (['comum' => ['aluno', 0], 'alunoadm' => ['aluno', 1], 'alunopar' => ['aluno', 1], 'professor' => ['administrador', 1], 'profpar' => ['administrador', 1], 'administracao' => ['administrador', 2], 'admpar' => ['administrador', 2], 'direcao' => ['administrador', 3], 'dirpar' => ['administrador', 3], 'sistema' => ['administrador', 4], 'bloqueado' => ['aluno', 0], 'legado' => ['administrador', 1]] as $chave => [$tipo, $nivel]) {
        $hash = $chave === 'legado' ? hash('sha256', $meta['password']) : \model\credenciais\Credenciais::hashSenha($meta['password']);
        $teste->prepare('INSERT INTO credenciais (usuario, senha, ativo, foto) VALUES (?, ?, ?, ?)')->execute(['qa' . $chave, $hash, $chave === 'bloqueado' ? 0 : 1, '/view/img/upload/perfil_usuario/ausente.jpg']);
        $cred = (int)$teste->lastInsertId();
        if ($tipo === 'aluno') {
            $teste->prepare("INSERT INTO aluno (nome, matricula, data_nascimento, id_serie, id_tipo_adm, id_credenciais) VALUES (?, ?, '2006-01-01', 1, ?, ?)")->execute(['Pessoa ' . $chave, (string)(1000 + $cred), $nivel ?: null, $cred]);
        } else {
            $teste->prepare('INSERT INTO administrador (nome, matricula, id_tipo_adm, id_credenciais) VALUES (?, ?, ?, ?)')->execute(['Pessoa ' . $chave, (string)(1000 + $cred), $nivel, $cred]);
        }
        $meta['actors'][$chave] = ['id' => (int)$teste->lastInsertId(), 'cred' => $cred, 'tipo' => $tipo, 'nivel' => $nivel, 'username' => 'qa' . $chave];
    }
    $autores = ['alunoadm', 'alunopar', 'professor', 'profpar', 'administracao', 'admpar', 'direcao', 'dirpar', 'sistema', 'legado'];
    foreach ($autores as $chave) {
        $autor = $meta['actors'][$chave];
        $coluna = $autor['tipo'] === 'aluno' ? 'id_aluno_adm' : 'id_administrador';
        $teste->prepare("INSERT INTO noticia (titulo, subtitulo, descricao, data_pub, status, $coluna) VALUES (?, '', 'Conteúdo fictício usado somente pelos testes.', ?, 1, ?)")->execute(['Publicacao ' . $chave, date('Y-m-d H:i:s'), $autor['id']]);
        $meta['publications']['noticia'][$chave] = (int)$teste->lastInsertId();
        $teste->prepare("INSERT INTO evento (titulo, data_evento, $coluna) VALUES (?, ?, ?)")->execute(['Evento ' . $chave, date('Y-m-d', strtotime('+1 day')), $autor['id']]);
        $meta['publications']['evento'][$chave] = (int)$teste->lastInsertId();
        $teste->prepare("INSERT INTO aviso (titulo, ativo, $coluna) VALUES (?, 1, ?)")->execute(['Aviso ' . $chave, $autor['id']]);
        $meta['publications']['aviso'][$chave] = (int)$teste->lastInsertId();
    }
    $prof = $meta['actors']['professor']['id'];
    for ($i = 0; $i < 2; $i++) {
        $teste->prepare("INSERT INTO noticia (titulo, descricao, status, id_administrador) VALUES (?, 'Texto de teste.', 1, ?)")->execute(['Extra ' . $i, $prof]);
    }
    $teste->prepare("INSERT INTO noticia (titulo, descricao, status, id_administrador) VALUES ('NAO_PUBLICAR_NOTICIA', 'Texto privado.', 0, ?)")->execute([$prof]);
    $meta['inactive_news'] = (int)$teste->lastInsertId();
    $teste->prepare("INSERT INTO aviso (titulo, ativo, id_administrador) VALUES ('NAO_PUBLICAR_AVISO', 0, ?)")->execute([$prof]);
    $teste->prepare("INSERT INTO evento (titulo, data_evento, id_administrador) VALUES ('NAO_PUBLICAR_EVENTO', '2000-01-01', ?)")->execute([$prof]);
    $img = imagecreatetruecolor(64, 64);
    imagefill($img, 0, 0, imagecolorallocate($img, 30, 90, 180));
    imagejpeg($img, $pasta . '/valid.jpg'); imagepng($img, $pasta . '/valid.png'); imagewebp($img, $pasta . '/valid.webp');
    file_put_contents($pasta . '/fixture.json', json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode($meta, JSON_THROW_ON_ERROR);
    exit;
}
$meta = json_decode(file_get_contents($pasta . '/fixture.json'), true, 512, JSON_THROW_ON_ERROR);
if (!preg_match('/^cetidam_test_[a-f0-9]{12}$/D', $meta['database'])) { throw new RuntimeException('Banco de teste inválido.'); }
if ($acao === 'cleanup') {
    if (isset($meta['db_user'])) {
        if ($meta['db_user'] !== $meta['database']) { throw new RuntimeException('Usuário de teste inválido.'); }
        $pdo->exec('DROP USER IF EXISTS ' . $pdo->quote($meta['db_user']) . "@'localhost'");
    }
    $pdo->exec('DROP DATABASE IF EXISTS `' . $meta['database'] . '`');
    unlink($pasta . '/fixture.json');
    echo '{"cleaned":true}';
    exit;
}
$teste = new PDO('mysql:host=' . $_ENV['db']['host'] . ';port=' . $_ENV['db']['port'] . ';dbname=' . $meta['database'] . ';charset=utf8mb4', $admin['user'], $admin['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
if ($acao === 'query') {
    $entrada = json_decode(stream_get_contents(STDIN), true, 512, JSON_THROW_ON_ERROR);
    $stmt = $teste->prepare($entrada['sql']);
    $stmt->execute($entrada['params'] ?? []);
    echo json_encode(['rows' => $stmt->columnCount() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [], 'last_id' => (int)$teste->lastInsertId()], JSON_THROW_ON_ERROR);
} elseif ($acao === 'expire') {
    $id = $argv[2] ?? '';
    if (!preg_match('/^[a-zA-Z0-9,-]+$/D', $id)) { throw new RuntimeException('Sessão de teste inválida.'); }
    session_save_path($pasta . '/storage/sessions');
    session_id($id); session_start(); $_SESSION['ultima_atividade'] = time() - 3601; session_write_close();
    echo '{"expired":true}';
}
