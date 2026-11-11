"""Regressão HTTP + MySQL real isolado. Executar: python tests/run.py [--keep]."""
from pathlib import Path
from urllib.request import Request, build_opener, HTTPRedirectHandler
from urllib.error import HTTPError, URLError
from urllib.parse import urlencode
import datetime as dt
import json
import os
import re
import socket
import subprocess
import sys
import time
import secrets

ROOT = Path(__file__).resolve().parents[1]
ART = ROOT / 'tests/.artifacts'
ART.mkdir(exist_ok=True)
RESULTS = []
PHP = ['php', '-d', 'display_errors=0']


def cli(action, payload=None, arg=None):
    command = PHP + ['tests/fixture.php', action] + ([arg] if arg else [])
    r = subprocess.run(command, cwd=ROOT, input=json.dumps(payload) if payload else None,
                       capture_output=True, text=True, encoding='utf-8')
    if r.returncode:
        raise RuntimeError(r.stderr + r.stdout)
    return json.loads(r.stdout)


def query(sql, params=()):
    return cli('query', {'sql': sql, 'params': list(params)})


def scalar(sql, params=()):
    return next(iter(query(sql, params)['rows'][0].values()))


def check(name, condition, detail=''):
    RESULTS.append({'teste': name, 'passou': bool(condition), 'detalhe': detail if not condition else ''})
    if not condition:
        print('FALHOU:', name, detail, flush=True)


class NoRedirect(HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


class Client:
    def __init__(self):
        self.cookie = ''
        self.token = ''
        self.opener = build_opener(NoRedirect())

    def request(self, path, data=None, files=None, method=None, csrf=True):
        headers = {'Cookie': self.cookie} if self.cookie else {}
        body = None
        if data is not None:
            fields = dict(data)
            if csrf:
                fields['csrf_token'] = self.token
            if files:
                boundary = 'CETIDAMTESTBOUNDARY'
                chunks = []
                for key, value in fields.items():
                    chunks.append(f'--{boundary}\r\nContent-Disposition: form-data; name="{key}"\r\n\r\n{value}\r\n'.encode())
                for field, filename, content, mime in files:
                    chunks.append(f'--{boundary}\r\nContent-Disposition: form-data; name="{field}"; filename="{filename}"\r\nContent-Type: {mime}\r\n\r\n'.encode() + content + b'\r\n')
                body = b''.join(chunks) + f'--{boundary}--\r\n'.encode()
                headers['Content-Type'] = 'multipart/form-data; boundary=' + boundary
            else:
                body = urlencode(fields).encode()
                headers['Content-Type'] = 'application/x-www-form-urlencoded'
        req = Request(BASE + path, data=body, headers=headers, method=method or ('POST' if data is not None else 'GET'))
        try:
            response = self.opener.open(req, timeout=20)
        except HTTPError as e:
            response = e
        raw = response.read()
        text = raw.decode('utf-8', errors='replace')
        for cookie in response.headers.get_all('Set-Cookie', []):
            if cookie.startswith('PHPSESSID='):
                self.cookie = cookie.split(';')[0]
        token = re.search(r'name="csrf_token" value="([a-f0-9]{64})"', text)
        if token:
            self.token = token[1]
        return response.code, response.headers, text

    def login(self, actor, next_url='/', password=None):
        self.request('/login')
        return self.request('/login?' + urlencode({'next_url': next_url}),
                            {'usuario': META['actors'][actor]['username'], 'senha': password or META['password']})


def allowed_publication(actor, owner):
    a, o = META['actors'][actor], META['actors'][owner]
    if a['nivel'] < 1:
        return False
    if actor == owner:
        return True
    return a['tipo'] == 'administrador' and (a['nivel'] == 4 or o['nivel'] < a['nivel'] or (o['tipo'] == 'aluno' and a['nivel'] == 1))


def allowed_user(actor, owner):
    a, o = META['actors'][actor], META['actors'][owner]
    return a['tipo'] == 'administrador' and actor != owner and o['nivel'] < 4 and o['nivel'] < a['nivel']


def user_payload(actor):
    a = META['actors'][actor]
    return {'id': a['id'], 'nome': 'Pessoa Atualizada', 'matricula': str(1000+a['cred']),
            'usuario': '', 'senha': '', 'confirmar_senha': '', 'data_nascimento': '2006-01-01', 'id_serie': '1',
            'id_credencial': META['actors']['sistema']['cred'], 'id_credenciais': META['actors']['sistema']['cred'],
            'id_tipo_adm': '4', 'nivel_acesso': '4'}


def execute_tests():
    visitor = Client()
    code, headers, home = visitor.request('/')
    check('Home pública', code == 200)
    check('Cabeçalhos de segurança', all(x in headers for x in ['Content-Security-Policy', 'X-Frame-Options', 'X-Content-Type-Options', 'Referrer-Policy']))
    check('Cookie HttpOnly/SameSite', 'HttpOnly' in headers.get('Set-Cookie', '') and 'SameSite=Lax' in headers.get('Set-Cookie', ''))
    check('Home oculta conteúdos indisponíveis', all(x not in home for x in ['NAO_PUBLICAR_NOTICIA', 'NAO_PUBLICAR_AVISO', 'NAO_PUBLICAR_EVENTO']))
    check('12 notícias geram 4 páginas', 'href="?pagina=4"' in home and 'href="?pagina=5"' not in home)
    for path in ['/?pagina=999', '/?pagina=-1', '/?pagina[]=1']:
        check('Paginação inválida ' + path, visitor.request(path)[0] == 200)
    for path in ['/cetidam', '/contato']:
        c, _, text = visitor.request(path)
        check('Página em desenvolvimento ' + path, c == 200 and 'está em desenvolvimento' in text)
    for path in ['/config.php', '/bootstrap.php', '/config.example.php', '/logs/log_2026_10.log', '/controller/Controller.php', '/model/aluno/Aluno.php', '/autoload.php', '/assets/manifest.json', '/.git/config', '/rota-inexistente', '/view/img/upload/arquivo.php']:
        check('Arquivos privados/rotas inexistentes ' + path, visitor.request(path)[0] == 404)
    for path in ['/assets/bootstrap/bootstrap.min.css', '/assets/bootstrap/bootstrap.bundle.min.js', '/assets/bootstrap-icons/bootstrap-icons.min.css', '/assets/bootstrap-icons/fonts/bootstrap-icons.woff2', '/view/img/upload/perfil_usuario/user_def.jpg']:
        check('Asset local ' + path, visitor.request(path)[0] == 200)
    check('Notícia inexistente 404', visitor.request('/noticias?id=999999')[0] == 404)
    check('Notícia inativa 404', visitor.request('/noticias?id='+str(META['inactive_news']))[0] == 404)
    for resource in ['noticia', 'evento', 'aviso', 'usuario']:
        check('Exclusão GET rejeitada ' + resource, visitor.request('/excluir/'+resource+'?id=1')[0] == 405)
        check('POST anônimo sem CSRF ' + resource, visitor.request('/excluir/'+resource, {'id': '1'}, csrf=False)[0] == 403)
    check('Logout GET rejeitado', visitor.request('/logout')[0] == 405)
    code, _, text = visitor.request('/login')
    before = visitor.cookie
    code, headers, _ = visitor.request('/login', {'usuario': "' OR '1'='1", 'senha': 'invalida'})
    check('SQL injection no login recusado', code == 302 and headers.get('Location') == '/login')
    code, headers, _ = visitor.request('/login', {'usuario': 'QAPROFESSOR', 'senha': META['password']})
    check('Login diferencia caixa', code == 302 and headers.get('Location') == '/login')
    code, headers, _ = visitor.login('professor', '/painel/noticia')
    check('Login retorna à rota interna', code == 302 and headers.get('Location') == '/painel/noticia')
    check('Login regenera identificador de sessão', visitor.cookie != before)
    check('Login não cria cookie de credencial', all(not h.startswith(('senha=', 'usuario=')) for h in headers.get_all('Set-Cookie', [])))
    for url in ['https://example.com', '//example.com', '/%2fexample.com', '/%5cexample.com', '/%0d%0aLocation:evil', 'javascript:alert(1)']:
        c = Client(); code, h, _ = c.login('professor', url)
        check('Redirecionamento externo recusado '+url, code == 302 and h.get('Location') == '/')
    blocked = Client(); blocked.login('bloqueado')
    check('Conta bloqueada informa bloqueio após senha correta', 'bloqueado' in blocked.request('/login')[2])
    wrong = Client(); wrong.login('bloqueado', password='SenhaErrada')
    check('Senha errada não revela conta bloqueada', 'Usuário ou senha incorretos' in wrong.request('/login')[2])
    legacy = Client(); code, _, _ = legacy.login('legado')
    check('Login legado funciona', code == 302)
    check('SHA-256 legado migra no login', len(scalar('SELECT senha FROM credenciais WHERE id=?', [META['actors']['legado']['cred']])) != 64)
    clients = {}
    actors = ['comum', 'alunoadm', 'alunopar', 'professor', 'profpar', 'administracao', 'admpar', 'direcao', 'dirpar', 'sistema']
    owners = actors[1:]
    tomorrow = (dt.date.today() + dt.timedelta(days=1)).isoformat()
    for actor in actors:
        client = clients[actor] = Client()
        check('Login '+actor, client.login(actor)[0] == 302)
        code, _, text = client.request('/perfil?id=999999')
        check('Perfil usa sessão '+actor, code == 200 and META['actors'][actor]['username'] in text and 'user_def.jpg' in text)
        check('Central administrativa '+actor, client.request('/painel/gerenciamento')[0] == (200 if META['actors'][actor]['tipo'] == 'administrador' else 403))
        check('Painel de logs '+actor, client.request('/painel/logs')[0] == (200 if actor == 'sistema' else 403))
        check('Edição própria '+actor, client.request('/atualizar/myself')[0] == (200 if META['actors'][actor]['tipo'] == 'administrador' else 302))
        for resource in ['noticia', 'evento', 'aviso']:
            code, _, text = client.request('/painel/'+resource)
            check('Painel '+resource+' '+actor, code == (200 if actor != 'comum' else 403))
            if actor != 'comum':
                ids = set(map(int, re.findall('/atualizar/'+resource+r'\?id=(\d+)', text)))
                for owner in owners:
                    check('Listagem autorizada '+resource+' '+actor+' -> '+owner, (META['publications'][resource][owner] in ids) == allowed_publication(actor, owner))
            for owner in owners:
                target = META['publications'][resource][owner]
                expected = 200 if allowed_publication(actor, owner) else 403
                c, _, _ = client.request('/atualizar/'+resource+'?id='+str(target))
                check('Autoriza GET '+resource+' '+actor+' -> '+owner, c == expected, str(c))
                data = {'id': target, 'titulo': 'Alteracao valida', 'subtitulo': '', 'descricao': 'Texto simples de teste.', 'status': '1', 'ativo': '1', 'data_evento': tomorrow,
                        'id_administrador': META['actors']['sistema']['id'], 'id_aluno_adm': '999999'}
                c, _, _ = client.request('/atualizar/'+resource, data)
                check('Autoriza POST '+resource+' '+actor+' -> '+owner, c == (302 if expected == 200 else 403), str(c))
                original = META['actors'][owner]
                column = 'id_aluno_adm' if original['tipo'] == 'aluno' else 'id_administrador'
                check('Autoria protegida '+resource+' '+actor+' -> '+owner, scalar('SELECT '+column+' FROM '+resource+' WHERE id=?', [target]) == original['id'])
        for owner in actors:
            target = META['actors'][owner]
            route = '/atualizar/usuario/'+target['tipo']
            expected = allowed_user(actor, owner)
            c, _, _ = client.request(route+'?id='+str(target['id']))
            check('Contas GET '+actor+' -> '+owner, c == (200 if expected else 403), str(c))
            c, _, _ = client.request(route, user_payload(owner))
            check('Contas POST '+actor+' -> '+owner, c == (302 if expected else 403), str(c))
            if target['tipo'] == 'administrador':
                check('Cargo protegido '+actor+' -> '+owner, scalar('SELECT id_tipo_adm FROM administrador WHERE id=?', [target['id']]) == target['nivel'])
    prof, sysc = clients['professor'], clients['sistema']
    # Campos e requisições inválidos não devem causar erro PHP/SQL ou persistência parcial.
    count = scalar('SELECT COUNT(*) FROM evento')
    for data in [{'titulo': 'abc', 'data_evento': tomorrow}, {'titulo': 'Título válido', 'data_evento': '2000-01-01'}, {'titulo': 'Título válido', 'data_evento': '2026-02-30'}, {'titulo': 'x'*51, 'data_evento': tomorrow}, {'titulo[]': 'valor', 'data_evento': tomorrow}]:
        check('Evento inválido recusado '+str(data), prof.request('/cadastro/evento', data)[0] == 422)
    check('Eventos inválidos não gravados', scalar('SELECT COUNT(*) FROM evento') == count)
    check('ID array recusado', prof.request('/atualizar/evento?id[]=1')[0] == 422)
    check('CSRF incorreto recusado', prof.request('/excluir/evento', {'id': 1, 'csrf_token': 'falso'}, csrf=False)[0] == 403)
    check('CSRF de outra sessão recusado', Client().request('/excluir/evento', {'id': 1, 'csrf_token': prof.token}, csrf=False)[0] == 403)
    for actor in ['alunoadm', 'professor', 'administracao', 'direcao', 'sistema']:
        client = clients[actor]
        for role in ['0', '1', '2', '3', '4', '999']:
            expected = META['actors'][actor]['tipo'] == 'administrador' and int(role) < META['actors'][actor]['nivel'] and int(role) <= 3
            username = 'novo'+actor+role
            data = {'nome': 'Pessoa Nova', 'matricula': '', 'usuario': username, 'senha': 'NovaSenha123!', 'confirmar_senha': 'NovaSenha123!', 'tipo_usuario': role, 'data_nascimento': '2006-01-01', 'id_serie': '1', 'id_tipo_adm': '4', 'nivel_acesso': '4'}
            c, _, _ = client.request('/cadastro/usuario', data)
            check('Cadastro de nível '+actor+' -> '+role, c == (302 if expected else 403), str(c))
            check('Cadastro persiste só se autorizado '+username, scalar('SELECT COUNT(*) FROM credenciais WHERE usuario=?', [username]) == int(expected))
    duplicate = {'nome': 'Pessoa Nova', 'matricula': '', 'usuario': META['actors']['professor']['username'], 'senha': 'NovaSenha123!', 'confirmar_senha': 'NovaSenha123!', 'tipo_usuario': '0', 'data_nascimento': '2006-01-01', 'id_serie': '1'}
    before_count = scalar('SELECT COUNT(*) FROM credenciais')
    check('Usuário duplicado retorna 409', prof.request('/cadastro/usuario', duplicate)[0] == 409)
    duplicate['usuario'] = 'matriculaduplicada'; duplicate['matricula'] = '1004'
    check('Matrícula duplicada recusada', prof.request('/cadastro/usuario', duplicate)[0] == 422)
    duplicate['matricula'] = ''; duplicate['confirmar_senha'] = 'diferente'
    check('Confirmação de senha validada no servidor', prof.request('/cadastro/usuario', duplicate)[0] == 422)
    duplicate['confirmar_senha'] = duplicate['senha']; duplicate['id_serie'] = '999'
    check('Série inexistente recusada', prof.request('/cadastro/usuario', duplicate)[0] == 422)
    check('Falhas de cadastro sem credencial órfã', scalar('SELECT COUNT(*) FROM credenciais') == before_count)
    # Uploads e XSS.
    upload_dir = ROOT / 'application/view/img/upload'
    before_files = set(upload_dir.rglob('*.jpg'))
    test_uploads = set()
    news = {'titulo': '<script>alert(1)</script>', 'subtitulo': '', 'descricao': '<img src=x onerror=alert(1)>\nTexto simples.'}
    for ext, mime in [('jpg', 'image/jpeg'), ('png', 'image/png'), ('webp', 'image/webp')]:
        c, _, _ = prof.request('/cadastro/noticia', news, [('img', 'teste.'+ext, (ART/('valid.'+ext)).read_bytes(), mime)])
        check('Upload válido '+ext, c == 302, str(c))
    for row in query('SELECT imagem FROM noticia WHERE imagem IS NOT NULL')['rows']:
        test_uploads.add(ROOT/'application'/row['imagem'].lstrip('/'))
    news_id = scalar('SELECT MAX(id) FROM noticia')
    c, _, text = visitor.request('/noticias?id='+str(news_id))
    check('XSS tratado como texto', c == 200 and '&lt;script&gt;alert(1)&lt;/script&gt;' in text and '<script>alert(1)</script>' not in text and '&lt;img src=x onerror=alert(1)&gt;' in text)
    image = scalar('SELECT imagem FROM noticia WHERE id=?', [news_id])
    check('Imagem recodificada como JPEG', image.endswith('.jpg') and (ROOT/'application'/image.lstrip('/')).read_bytes().startswith(b'\xff\xd8'))
    edit = dict(news, id=news_id, status='0')
    check('Notícia atualiza sem nova imagem', prof.request('/atualizar/noticia', edit)[0] == 302 and scalar('SELECT imagem FROM noticia WHERE id=?', [news_id]) == image)
    check('Notícia desativada não acessível', visitor.request('/noticias?id='+str(news_id))[0] == 404)
    count = scalar('SELECT COUNT(*) FROM noticia')
    for content, label in [(b'<?php echo "ataque"; ?>', 'PHP disfarçado'), (b'not-an-image', 'corrompido'), (b'a'*(10*1024*1024+1), 'maior que 10 MB')]:
        c, _, _ = prof.request('/cadastro/noticia', news, [('img', 'teste.jpg', content, 'image/jpeg')])
        check('Upload recusado '+label, c == 422, str(c))
    check('Uploads inválidos sem registro parcial', scalar('SELECT COUNT(*) FROM noticia') == count)
    # Falha real do banco depois de gravar arquivo precisa limpar o arquivo novo.
    files = set(upload_dir.rglob('*.jpg'))
    query("CREATE TRIGGER qa_falha_noticia BEFORE INSERT ON noticia FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='falha controlada'")
    c, _, text = prof.request('/cadastro/noticia', news, [('img', 'valid.jpg', (ART/'valid.jpg').read_bytes(), 'image/jpeg')])
    query('DROP TRIGGER qa_falha_noticia')
    check('Erro interno genérico', c == 500 and 'SQLSTATE' not in text and 'falha controlada' not in text)
    check('Erro de insert limpa upload', set(upload_dir.rglob('*.jpg')) == files and scalar('SELECT COUNT(*) FROM noticia') == count)
    target = META['actors']['comum']; oldname = scalar('SELECT nome FROM aluno WHERE id=?', [target['id']])
    query("CREATE TRIGGER qa_falha_cred BEFORE UPDATE ON credenciais FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='falha controlada'")
    files = set(upload_dir.rglob('*.jpg'))
    data = user_payload('comum'); data['nome'] = 'Nome Alterado'
    c, _, _ = prof.request('/atualizar/usuario/aluno', data, [('foto', 'valid.jpg', (ART/'valid.jpg').read_bytes(), 'image/jpeg')])
    query('DROP TRIGGER qa_falha_cred')
    check('Edição de usuário reverte transação e foto', c == 500 and scalar('SELECT nome FROM aluno WHERE id=?', [target['id']]) == oldname and set(upload_dir.rglob('*.jpg')) == files)
    # Troca da foto de subordinado preserva a foto do gestor e a imagem padrão.
    system_photo = '/view/img/upload/perfil_usuario/10_'+secrets.token_hex(8)+'.jpg'
    old_photo = '/view/img/upload/perfil_usuario/1_'+secrets.token_hex(8)+'.jpg'
    for image_path in [system_photo, old_photo]:
        test_path = ROOT/'application'/image_path.lstrip('/')
        with test_path.open('xb') as file: file.write((ART/'valid.jpg').read_bytes())
        test_uploads.add(test_path)
    query('UPDATE credenciais SET foto=? WHERE id=?', [system_photo, META['actors']['sistema']['cred']])
    query('UPDATE credenciais SET foto=? WHERE id=?', [old_photo, target['cred']])
    c, _, _ = sysc.request('/atualizar/usuario/aluno', user_payload('comum'), [('foto', 'valid.png', (ART/'valid.png').read_bytes(), 'image/png')])
    test_uploads.add(ROOT/'application'/scalar('SELECT foto FROM credenciais WHERE id=?', [target['cred']]).lstrip('/'))
    check('Foto correta removida após edição', c == 302 and not (ROOT/'application'/old_photo.lstrip('/')).exists() and (ROOT/'application'/system_photo.lstrip('/')).exists())
    check('Foto padrão preservada', (upload_dir/'perfil_usuario/user_def.jpg').is_file())
    # Bloqueio atinge sessão já aberta; reativação mantém senha.
    c, _, _ = prof.request('/painel/acesso/usuario', {'id_credenciais': target['cred'], 'ativo': '0'})
    check('Bloqueio persiste', c == 302 and scalar('SELECT ativo FROM credenciais WHERE id=?', [target['cred']]) == 0)
    c, h, _ = clients['comum'].request('/perfil')
    check('Bloqueio revoga sessão aberta', c == 302 and h.get('Location') == '/login')
    check('Reativação persiste', prof.request('/painel/acesso/usuario', {'id_credenciais': target['cred'], 'ativo': '1'})[0] == 302)
    check('Senha original funciona após reativação', Client().login('comum')[1].get('Location') == '/')
    for actor, owner in [('professor', 'profpar'), ('professor', 'alunoadm'), ('sistema', 'sistema'), ('direcao', 'sistema')]:
        c = clients[actor]; o = META['actors'][owner]
        check('Bloqueio hierarquia '+actor+' -> '+owner, c.request('/painel/acesso/usuario', {'id_credenciais': o['cred'], 'ativo': '0'})[0] == 403)
        check('Exclusão hierarquia '+actor+' -> '+owner, c.request('/excluir/usuario', {'id': o['id'], 'tipo_usuario': o['tipo']})[0] == 403)
    # ID de credencial forjado no perfil próprio é ignorado.
    sys_hash = scalar('SELECT senha FROM credenciais WHERE id=?', [META['actors']['sistema']['cred']])
    prof_actor = META['actors']['professor']; own = user_payload('professor'); own['id'] = META['actors']['sistema']['id']; own['nome'] = 'Professor Atualizado'
    check('Perfil próprio ignora IDs forjados', prof.request('/atualizar/myself', own)[0] == 302 and scalar('SELECT nome FROM administrador WHERE id=?', [prof_actor['id']]) == 'Professor Atualizado' and scalar('SELECT senha FROM credenciais WHERE id=?', [META['actors']['sistema']['cred']]) == sys_hash)
    check('Senha vazia preservada', Client().login('professor')[1].get('Location') == '/')
    # Exclusão remove dependências das três tabelas e foto apenas depois do commit.
    victim = META['actors']['alunopar']
    check('Exclusão subordinado com publicações', sysc.request('/excluir/usuario', {'id': victim['id'], 'tipo_usuario': 'aluno'})[0] == 302)
    check('Exclusão sem credencial órfã', scalar('SELECT COUNT(*) FROM credenciais WHERE id=?', [victim['cred']]) == 0)
    for resource in ['noticia', 'evento', 'aviso']:
        check('Exclusão remove '+resource+' associado', scalar('SELECT COUNT(*) FROM '+resource+' WHERE id_aluno_adm=?', [victim['id']]) == 0)
    # Exclusão de cada publicação por POST e proprietário.
    for resource in ['noticia', 'evento', 'aviso']:
        identifier = META['publications'][resource]['profpar']
        check('Exclusão de publicação autorizada '+resource, clients['profpar'].request('/excluir/'+resource, {'id': identifier})[0] == 302 and scalar('SELECT COUNT(*) FROM '+resource+' WHERE id=?', [identifier]) == 0)
    check('Exclusão de notícia remove arquivo', prof.request('/excluir/noticia', {'id': news_id})[0] == 302 and not (ROOT/'application'/image.lstrip('/')).exists())
    timeout = Client(); timeout.login('professor'); cli('expire', arg=timeout.cookie.split('=', 1)[1])
    check('Sessão expira por inatividade', timeout.request('/perfil')[1].get('Location') == '/login')
    other = Client(); other.login('professor')
    own = user_payload('professor'); own.update(senha='SenhaAlterada123!', confirmar_senha='SenhaAlterada123!')
    check('Troca da própria senha', prof.request('/atualizar/myself', own)[0] == 302)
    check('Troca de senha revoga outras sessões', other.request('/perfil')[1].get('Location') == '/login')
    check('Sessão atual continua após troca', prof.request('/perfil')[0] == 200)
    check('Nova senha autentica', Client().login('professor', password='SenhaAlterada123!')[1].get('Location') == '/')
    c, h, _ = prof.request('/logout', {})
    check('Logout destrói sessão e cookie', c == 302 and 'PHPSESSID=deleted' in h.get('Set-Cookie', '') and prof.request('/perfil')[1].get('Location', '').startswith('/login'))
    logs = ''.join(p.read_text(encoding='utf-8') for p in (ART/'storage/logs').glob('log_*.log'))
    check('Auditoria registra ações', all(x in logs for x in ['login', 'usuario_bloqueado', 'usuario_reativado', 'usuario_excluido', 'noticia_cadastrada', 'evento_excluido', 'aviso_excluido']))
    check('Logs não contêm senhas/hashes', META['password'] not in logs and 'SenhaAlterada123!' not in logs and '$2y$' not in logs and '$argon2' not in logs)
    # Limpeza somente dos arquivos novos gerados por esta suíte.
    for path in test_uploads - before_files:
        if path.is_file(): path.unlink()


if __name__ == '__main__':
    if (ART/'fixture.json').exists():
        cli('cleanup')
    source_before = cli('snapshot')
    META = cli('setup')
    env = dict(os.environ, CETIDAM_DB_NAME=META['database'], CETIDAM_STORAGE_DIR=str(ART/'storage'), CETIDAM_BACKUP_DIR=str(ART/'storage/backups'))
    migration = subprocess.run(PHP + ['scripts/migrar_banco.php', '--apply', '--admin'], cwd=ROOT, env=env, capture_output=True, text=True, encoding='utf-8')
    if migration.returncode:
        raise RuntimeError(migration.stderr + migration.stdout)
    env.update(CETIDAM_DB_USER=META['db_user'], CETIDAM_DB_PASS=META['db_pass'])
    with socket.socket() as s:
        s.bind(('127.0.0.1', 0)); port = s.getsockname()[1]
    BASE = 'http://127.0.0.1:'+str(port)
    server_log = open(ART/'server.log', 'w', encoding='utf-8')
    server = subprocess.Popen(PHP+['-d', 'upload_max_filesize=10M', '-d', 'post_max_size=12M', '-S', '127.0.0.1:'+str(port), '-t', 'application', 'router.php'], cwd=ROOT, env=env, stdout=server_log, stderr=server_log, creationflags=getattr(subprocess, 'CREATE_NO_WINDOW', 0))
    try:
        for _ in range(80):
            try:
                Client().request('/login'); break
            except URLError:
                time.sleep(.1)
        execute_tests()
    finally:
        server.terminate(); server.wait(timeout=10); server_log.close()
        check('Registros da base original preservados', source_before == cli('snapshot'))
        report = {'data': dt.datetime.now().isoformat(timespec='seconds'), 'total': len(RESULTS), 'aprovados': sum(r['passou'] for r in RESULTS), 'falhas': sum(not r['passou'] for r in RESULTS), 'resultados': RESULTS}
        (ART/'results.json').write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding='utf-8')
        print(json.dumps({k: v for k, v in report.items() if k != 'resultados'}, ensure_ascii=False), flush=True)
        if '--keep' not in sys.argv:
            cli('cleanup')
    sys.exit(0 if all(r['passou'] for r in RESULTS) else 1)
