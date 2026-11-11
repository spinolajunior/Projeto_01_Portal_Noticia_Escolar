"""Compatibilidade do config original e transição do Git, sem banco real."""
from pathlib import Path
import json
import os
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[1]
results = []


def check(name, condition):
    results.append({'teste': name, 'passou': bool(condition)})


def git(folder, *args, required=True):
    run = subprocess.run(['git', '-c', 'core.autocrlf=false', '-c', 'commit.gpgsign=false',
                          '-c', 'user.name=Teste CETIDAM', '-c', 'user.email=teste@example.invalid', *args],
                         cwd=folder, capture_output=True, text=True, encoding='utf-8')
    if required and run.returncode:
        raise RuntimeError('Comando Git da simulação falhou: ' + ' '.join(args))
    return run


with tempfile.TemporaryDirectory(prefix='cetidam-config-git-') as directory:
    root = Path(directory)
    source = root/'origem'; source.mkdir()
    (source/'application').mkdir()
    original = """<?php
$_ENV['db'] = ['host'=>'localhost:3307','dbname'=>'escola_teste','user'=>'usuario_ficticio','pass'=>'senha_ficticia'];
define('BASE_DIR', __DIR__ . '/');
define('VIEW', BASE_DIR . 'view/');
"""
    config = source/'application/config.php'
    config.write_text(original, encoding='utf-8')
    (source/'application/bootstrap.php').write_bytes((ROOT/'application/bootstrap.php').read_bytes())
    probe = source/'probe.php'
    probe.write_text("<?php require __DIR__.'/application/bootstrap.php'; echo json_encode(['port'=>$_ENV['db']['port'],'dsn'=>$_ENV['db']['dsn'],'base'=>BASE_DIR,'view'=>VIEW,'private'=>PRIVATE_DIR,'timeout'=>SESSION_TIMEOUT]);", encoding='utf-8')
    env = {k:v for k,v in os.environ.items() if not k.startswith('CETIDAM_')}
    run = subprocess.run(['php', '-d', 'display_errors=0', str(probe)], capture_output=True, text=True, encoding='utf-8', env=env)
    values = json.loads(run.stdout)
    check('Config original define caminhos sem redefinição', run.returncode == 0 and 'already defined' not in run.stderr and Path(values['base']).resolve() == (source/'application').resolve())
    check('Host e porta antigos geram DSN utf8mb4', values['port'] == '3307' and values['dsn'] == 'mysql:host=localhost;port=3307;dbname=escola_teste;charset=utf8mb4')
    check('Inicialização mantém sessões e logs privados', values['timeout'] == 1800 and Path(values['private']).resolve() == (source/'storage').resolve())
    git(source, 'init', '--initial-branch=production')
    git(source, 'add', '.')
    git(source, 'commit', '-m', 'Versao ficticia anterior')
    git(root, 'clone', '--quiet', str(source), 'escola')
    school = root/'escola'
    school_config = school/'application/config.php'
    school_data = original.replace('usuario_ficticio', 'usuario_escola_ficticio').encode('utf-8')
    school_config.write_bytes(school_data)
    (source/'.gitignore').write_text('/application/config.php\n', encoding='utf-8')
    git(source, 'rm', '--cached', '--', 'application/config.php')
    git(source, 'add', '.gitignore')
    git(source, 'commit', '-m', 'Configuracao privada')
    check('Retirada do índice preserva arquivo da origem', config.read_text(encoding='utf-8') == original)
    blocked = git(school, 'pull', '--ff-only', 'origin', 'production', required=False)
    check('Primeiro pull identifica alteração local', blocked.returncode != 0 and school_config.read_bytes() == school_data)
    backup = root/'config-escola.php'; backup.write_bytes(school_config.read_bytes())
    git(school, 'restore', '--source=HEAD', '--staged', '--worktree', '--', 'application/config.php')
    git(school, 'pull', '--ff-only', 'origin', 'production')
    check('Primeiro pull remove antiga configuração versionada', not school_config.exists())
    school_config.write_bytes(backup.read_bytes())
    check('Procedimento restaura credenciais da escola', school_config.read_bytes() == school_data)
    check('Configuração deixa de ser rastreada', not git(school, 'ls-files', '--', 'application/config.php').stdout.strip())
    check('Configuração restaurada fica ignorada', git(school, 'check-ignore', '--', 'application/config.php').stdout.strip() == 'application/config.php')
    git(school, 'add', '.')
    check('Add comum não prepara a configuração', not git(school, 'diff', '--cached', '--name-only').stdout.strip())
    (source/'README.md').write_text('Atualizacao ficticia\n', encoding='utf-8')
    git(source, 'add', 'README.md'); git(source, 'commit', '-m', 'Proxima atualizacao')
    git(school, 'pull', '--ff-only', 'origin', 'production')
    check('Pull seguinte preserva configuração local', school_config.read_bytes() == school_data)

print(json.dumps({'total':len(results), 'aprovados':sum(r['passou'] for r in results),
                  'falhas':[r['teste'] for r in results if not r['passou']]}, ensure_ascii=False))
raise SystemExit(0 if all(r['passou'] for r in results) else 1)
