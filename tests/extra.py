"""Casos adicionais após a matriz completa: python tests/extra.py."""
import run as t
from html.parser import HTMLParser
from pathlib import Path
from urllib.error import URLError
import datetime as dt
import json
import os
import socket
import struct
import subprocess
import time
import zlib


class Forms(HTMLParser):
    def __init__(self):
        super().__init__(); self.forms = []; self.current = None; self.nested = False

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == 'form':
            if self.current is not None: self.nested = True
            self.current = {'attrs': attrs, 'inputs': []}; self.forms.append(self.current)
        elif tag == 'input' and self.current is not None:
            self.current['inputs'].append(attrs)

    def handle_endtag(self, tag):
        if tag == 'form': self.current = None


def execute():
    c = t.Client(); c.login('sistema'); c.request('/perfil')
    # Tipografia e feedback usam os mesmos templates e recursos locais do portal.
    import re
    from urllib.request import urlopen
    _, _, html = c.request('/painel/usuarios')
    t.check('Poppins e estilos globais presentes no template', '/assets/fonts/poppins/poppins.css' in html and '/assets/portal.css' in html)
    font_css = t.Client().request('/assets/fonts/poppins/poppins.css')[2]
    t.check('Poppins oferece seis pesos e itálico', all('font-weight: '+str(weight) in font_css for weight in [300,400,500,600,700,800]) and 'font-style: italic' in font_css)
    font_paths = re.findall(r'url\(\./([^)]+\.woff2)\)', font_css)
    t.check('Poppins possui subconjuntos latinos locais', len(font_paths) == 14 and not re.search(r'url\(https?://', font_css))
    for name in font_paths:
        with urlopen(t.BASE+'/assets/fonts/poppins/'+name, timeout=10) as response:
            t.check('Fonte WOFF2 disponível '+name, response.status == 200 and response.read(4) == b'wOF2')
    t.check('Aviso de exclusão informa todas as publicações', all(text in html for text in ['As publicações também serão apagadas','Todas as notícias, os eventos e os avisos','incluindo as imagens das notícias','Essa ação não poderá ser desfeita pelo portal.']))
    t.check('Uma confirmação acessível atende desktop e celular', html.count('id="excluirUsuarioModal"') == 1 and 'aria-describedby="descricaoExcluirUsuario"' in html and 'id="cancelarExcluirUsuario"' in html and 'id="confirmarExcluirUsuario"' in html)
    t.check('Nome na confirmação tratado como texto', "document.getElementById('nomeExcluirUsuario').textContent" in html and 'Deseja realmente excluir o ' not in html)
    for values, phrase in [({'titulo':'','data_evento':'2027-01-01'},'Preencha o campo “Título”'),({'titulo':'ab','data_evento':'2027-01-01'},'pelo menos 5 caracteres'),({'titulo':'A'*51,'data_evento':'2027-01-01'},'aceita até 50 caracteres')]:
        code, _, feedback = c.request('/cadastro/evento', values)
        t.check('Mensagem amigável '+phrase, code == 422 and phrase in feedback and 'bi-exclamation-triangle-fill' in feedback and 'Voltar e corrigir' in feedback and 'role="alert"' in feedback)
    visitor = t.Client(); visitor.request('/login')
    visitor.request('/login', {'usuario':'qainexistente','senha':'senhaerrada'})
    _, _, feedback = visitor.request('/login')
    t.check('Mensagem de erro permanece até ser fechada', 'data-auto-fechar="false"' in feedback and 'Precisamos da sua atenção' in feedback)
    base_account = {'nome':'Pessoa Nova','matricula':'','usuario':'qacadastrofeedback','senha':'SenhaTesteNova123!','confirmar_senha':'SenhaTesteNova123!','tipo_usuario':'0','data_nascimento':'2006-01-01','id_serie':'1'}
    account_count = t.scalar('SELECT COUNT(*) FROM credenciais')
    for changes, phrase in [({'nome':'Pessoa 123'},'Nome completo'),({'matricula':'12A'},'apenas números'),({'usuario':'nome_invalido'},'sem espaços, acentos ou símbolos'),({'senha':'curta','confirmar_senha':'curta'},'pelo menos 8 caracteres'),({'senha':'qacadastrofeedback','confirmar_senha':'qacadastrofeedback'},'diferente do nome de usuário'),({'senha':'á'*40,'confirmar_senha':'á'*40},'ficou longa demais'),({'confirmar_senha':'outra senha'},'As duas senhas ficaram diferentes')]:
        code, _, feedback = c.request('/cadastro/usuario', dict(base_account, **changes))
        t.check('Orientação específica do cadastro '+phrase, code == 422 and phrase in feedback and 'Voltar e corrigir' in feedback)
    t.check('Validações amigáveis não gravam conta parcial', t.scalar('SELECT COUNT(*) FROM credenciais') == account_count)
    dates = (dt.date.today()+dt.timedelta(days=2)).isoformat()
    upload = t.ROOT/'application/view/img/upload'
    files_before = set(upload.rglob('*.jpg'))
    for actor in ['alunoadm', 'sistema']:
        client = t.Client(); client.login(actor); client.request('/perfil')
        for resource, values in [('evento', {'titulo': 'Evento novo', 'data_evento': dates}), ('aviso', {'titulo': 'A'*80})]:
            code, _, _ = client.request('/cadastro/'+resource, values)
            owner = t.META['actors'][actor]
            row = t.query('SELECT * FROM '+resource+' ORDER BY id DESC LIMIT 1')['rows'][0]
            column = 'id_aluno_adm' if owner['tipo'] == 'aluno' else 'id_administrador'
            t.check('Cadastro válido/autoria '+resource+' '+actor, code == 302 and row[column] == owner['id'], str(code))
            if resource == 'aviso':
                marker = 'AVISO_STATUS_' + actor
                code, _, _ = client.request('/atualizar/aviso', {'id':row['id'],'titulo':marker,'ativo':'0'})
                t.check('Aviso desativa e sai da Home '+actor, code == 302 and t.scalar('SELECT ativo FROM aviso WHERE id=?',[row['id']]) == 0 and marker not in t.Client().request('/')[2], str(code))
                code, _, _ = client.request('/atualizar/aviso', {'id':row['id'],'titulo':marker,'ativo':'1'})
                t.check('Aviso reativa e volta à Home '+actor, code == 302 and t.scalar('SELECT ativo FROM aviso WHERE id=?',[row['id']]) == 1 and marker in t.Client().request('/')[2], str(code))
            t.check('Exclusão do cadastro novo '+resource+' '+actor, client.request('/excluir/'+resource, {'id': row['id']})[0] == 302)
    for actor in ['comum', 'alunoadm']:
        client = t.Client(); client.login(actor)
        html = client.request('/perfil')[2]
        t.check('Aluno não recebe ação de editar perfil '+actor, '/atualizar/myself' not in html)
        t.check('Aluno não recebe central administrativa '+actor, '/painel/gerenciamento' not in html)
    for actor, roles in [('professor', {'0'}), ('administracao', {'0','1'}), ('direcao', {'0','1','2'}), ('sistema', {'0','1','2','3'})]:
        client = t.Client(); client.login(actor, password='SenhaAlterada123!' if actor == 'professor' else None)
        import re
        html = client.request('/cadastro/usuario')[2]
        select = re.search(r'<select[^>]*name="tipo_usuario"[^>]*>([\s\S]*?)</select>', html)
        offered = set(re.findall(r'<option value="(\d+)"', select[1]))
        t.check('Formulário oferece somente cargos permitidos '+actor, offered == roles)
    # PNG com cabeçalho válido e dimensões acima do limite; não precisa alocar a imagem.
    def chunk(name, data):
        return struct.pack('>I', len(data))+name+data+struct.pack('>I', zlib.crc32(name+data))
    large_png = b'\x89PNG\r\n\x1a\n'+chunk(b'IHDR', struct.pack('>IIBBBBB', 4097, 4097, 8, 2, 0, 0, 0))+chunk(b'IEND', b'')
    news = {'titulo': 'Noticia de teste', 'subtitulo': '', 'descricao': 'Texto.'}
    count = t.scalar('SELECT COUNT(*) FROM noticia')
    for content, label in [(large_png, 'acima de 16 megapixels'), ((t.ART/'valid.png').read_bytes()[:40], 'PNG truncado'), ((t.ART/'valid.jpg').read_bytes(), 'img com estrutura array')]:
        name = 'img[campo]' if label.endswith('array') else 'img'
        code, _, _ = c.request('/cadastro/noticia', news, [(name, 'teste.png', content, 'image/png')])
        t.check('Imagem rejeitada '+label, code == 422, str(code))
    t.check('Imagens rejeitadas sem registro/arquivo órfão', t.scalar('SELECT COUNT(*) FROM noticia') == count and set(upload.rglob('*.jpg')) == files_before)
    # Preservação de imagem em falha de UPDATE e remoção após substituição válida.
    code, _, _ = c.request('/cadastro/noticia', news, [('img', 'valid.jpg', (t.ART/'valid.jpg').read_bytes(), 'image/jpeg')])
    identifier = t.scalar('SELECT MAX(id) FROM noticia')
    old_image = t.scalar('SELECT imagem FROM noticia WHERE id=?', [identifier])
    t.query("CREATE TRIGGER qa_falha_update BEFORE UPDATE ON noticia FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='falha controlada'")
    body = dict(news, id=identifier, status='1', titulo='Titulo alterado')
    code, _, _ = c.request('/atualizar/noticia', body, [('imagem', 'valid.png', (t.ART/'valid.png').read_bytes(), 'image/png')])
    t.query('DROP TRIGGER qa_falha_update')
    t.check('Falha de UPDATE preserva notícia/imagem anterior', code == 500 and t.scalar('SELECT titulo FROM noticia WHERE id=?', [identifier]) == news['titulo'] and t.scalar('SELECT imagem FROM noticia WHERE id=?', [identifier]) == old_image and (t.ROOT/'application'/old_image.lstrip('/')).exists())
    files = set(upload.rglob('*.jpg'))
    code, _, _ = c.request('/atualizar/noticia', body, [('imagem', 'valid.webp', (t.ART/'valid.webp').read_bytes(), 'image/webp')])
    new_image = t.scalar('SELECT imagem FROM noticia WHERE id=?', [identifier])
    t.check('Substituição válida remove somente imagem anterior', code == 302 and new_image != old_image and not (t.ROOT/'application'/old_image.lstrip('/')).exists() and (t.ROOT/'application'/new_image.lstrip('/')).exists())
    c.request('/excluir/noticia', {'id': identifier})
    # HTML gerado, formulários e nonces reais, sem executar um navegador.
    target = t.META['actors']['comum']['id']
    routes = ['/', '/login', '/perfil', '/cadastro/noticia', '/cadastro/evento', '/cadastro/aviso', '/cadastro/usuario', '/atualizar/myself', '/atualizar/usuario/aluno?id='+str(target), '/painel/noticia', '/painel/evento', '/painel/aviso', '/painel/usuarios', '/painel/acesso/usuario', '/painel/gerenciamento']
    import re
    for route in routes:
        code, headers, html = c.request(route)
        forms = Forms(); forms.feed(html)
        post = [f for f in forms.forms if f['attrs'].get('method', '').upper() == 'POST']
        t.check('HTML/formulários protegidos '+route, code == 200 and not forms.nested and all(any(i.get('name') == 'csrf_token' and len(i.get('value', '')) == 64 for i in f['inputs']) for f in post))
        nonce = re.search(r"script-src 'self' 'nonce-([^']+)'", headers.get('Content-Security-Policy',''))
        scripts = re.findall(r'<script\b([^>]*)>', html)
        t.check('CSP e scripts autorizados '+route, nonce and all('src=' in s or 'nonce="'+nonce[1]+'"' in s for s in scripts) and not re.search(r'\son(?:error|click)=', html))
    # Sidebar deve continuar disponível se não houver notícias.
    t.query('UPDATE noticia SET status=0')
    t.query("INSERT INTO aviso (titulo, ativo, id_administrador) VALUES ('AVISO_SEM_NOTICIA', 1, ?)", [t.META['actors']['sistema']['id']])
    t.query("INSERT INTO evento (titulo, data_evento, id_administrador) VALUES ('EVENTO_SEM_NOTICIA', ?, ?)", [dates, t.META['actors']['sistema']['id']])
    html = t.Client().request('/')[2]
    t.check('Home vazia preserva eventos e avisos', 'AVISO_SEM_NOTICIA' in html and 'EVENTO_SEM_NOTICIA' in html)
    t.query('UPDATE noticia SET status=1')
    t.query("INSERT INTO evento (titulo,data_evento,id_administrador) VALUES ('EVENTO_LONGE',?,?)", [(dt.date.today()+dt.timedelta(days=10)).isoformat(),t.META['actors']['sistema']['id']])
    t.query("INSERT INTO evento (titulo,data_evento,id_administrador) VALUES ('EVENTO_PERTO',?,?)", [dates,t.META['actors']['sistema']['id']])
    t.query("INSERT INTO aviso (titulo,ativo,id_administrador) VALUES ('AVISO_ANTIGO',1,?)", [t.META['actors']['sistema']['id']])
    t.query("INSERT INTO aviso (titulo,ativo,id_administrador) VALUES ('AVISO_RECENTE',1,?)", [t.META['actors']['sistema']['id']])
    html=t.Client().request('/')[2]
    t.check('Eventos ordenados pela data mais próxima', html.index('EVENTO_PERTO') < html.index('EVENTO_LONGE'))
    t.check('Avisos ordenados do mais recente para o mais antigo', html.index('AVISO_RECENTE') < html.index('AVISO_ANTIGO'))
    # Níveis desconhecidos não recebem acesso administrativo por comparação >=.
    t.query('DELETE FROM administrador WHERE id_tipo_adm=99')
    t.query("DELETE FROM credenciais WHERE usuario='qanivelinvalido'")
    t.query('DELETE FROM tipo_adm WHERE id=99')
    t.query("INSERT INTO tipo_adm (id,cargo,nivel_acesso) VALUES (99,'Nivel invalido de teste',7)")
    t.query("INSERT INTO credenciais (usuario,senha) SELECT 'qanivelinvalido',senha FROM credenciais WHERE id=?", [t.META['actors']['sistema']['cred']])
    credential = t.scalar("SELECT id FROM credenciais WHERE usuario='qanivelinvalido'")
    t.query("INSERT INTO administrador (nome,id_credenciais,id_tipo_adm) VALUES ('Pessoa Invalida',?,99)", [credential])
    invalid = t.Client(); invalid.request('/login')
    code, h, _ = invalid.request('/login', {'usuario': 'qanivelinvalido', 'senha': t.META['password']})
    t.check('Nível administrativo fora de 0 a 4 recusado', code == 302 and h.get('Location') == '/login' and invalid.request('/perfil')[0] == 302)
    grants = t.cli('runtime_grants')
    t.check('Aplicação real usa somente privilégios DML', any('SELECT, INSERT, UPDATE, DELETE ON' in g for g in grants) and all('ALL PRIVILEGES' not in g and 'GRANT OPTION' not in g for g in grants))
    restored = t.cli('restore')
    t.check('Backup sintético restaura as 8 tabelas atuais', len(restored) == 8 and 'escola' not in restored and restored['credenciais'] == 12 and restored['noticia'] == 13 and restored['aluno'] == 4)
    t.check('Tabela escola ausente da base ativa', 'escola' not in t.cli('snapshot'))
    t.check('Tabela escola ausente da base de teste', t.scalar("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='escola'") == 0)
    t.check('Todos os uploads desta rodada removidos', set(upload.rglob('*.jpg')) == files_before)


if __name__ == '__main__':
    t.META = json.loads((t.ART/'fixture.json').read_text(encoding='utf-8'))
    env = dict(os.environ, CETIDAM_DB_NAME=t.META['database'], CETIDAM_DB_USER=t.META['db_user'], CETIDAM_DB_PASS=t.META['db_pass'], CETIDAM_STORAGE_DIR=str(t.ART/'storage'))
    with socket.socket() as s:
        s.bind(('127.0.0.1',0)); port=s.getsockname()[1]
    t.BASE='http://127.0.0.1:'+str(port)
    log=open(t.ART/'extra-server.log','w',encoding='utf-8')
    server=subprocess.Popen(t.PHP+['-d','upload_max_filesize=10M','-d','post_max_size=12M','-S','127.0.0.1:'+str(port),'-t','application','router.php'],cwd=t.ROOT,env=env,stdout=log,stderr=log,creationflags=getattr(subprocess,'CREATE_NO_WINDOW',0))
    before=t.cli('snapshot')
    try:
        for _ in range(80):
            try:t.Client().request('/login');break
            except URLError:time.sleep(.1)
        execute()
    finally:
        server.terminate();server.wait(timeout=10);log.close()
        t.check('Dados originais preservados nos testes extras', before == t.cli('snapshot'))
        report={'total':len(t.RESULTS),'aprovados':sum(r['passou'] for r in t.RESULTS),'falhas':sum(not r['passou'] for r in t.RESULTS),'resultados':t.RESULTS}
        (t.ART/'extra-results.json').write_text(json.dumps(report,ensure_ascii=False,indent=2),encoding='utf-8')
        print(json.dumps({k:v for k,v in report.items() if k!='resultados'},ensure_ascii=False))
    raise SystemExit(0 if all(r['passou'] for r in t.RESULTS) else 1)
