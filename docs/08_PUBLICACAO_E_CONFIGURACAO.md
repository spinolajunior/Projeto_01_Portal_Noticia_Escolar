# Publicação e preservação do config.php

## Decisão e motivo

Por solicitação do responsável, o banco continua configurado diretamente em `application/config.php`, na estrutura `$_ENV['db']` original. Esse arquivo é privado e não deve subir ao repositório. `application/config.example.php` fornece somente um exemplo sem senhas reais. Não há mais dependência de `config/local.php`.

`application/bootstrap.php` carrega o config.php de cada máquina e concentra a inicialização sem segredos: PDO/utf8mb4, compatibilidade com host:porta, constantes e armazenamento privado. Preserva BASE_DIR e VIEW quando definidos pelo config.php antigo. A entrada web e as ferramentas CLI reutilizam esse arquivo, sem novas classes ou funções.

O `.gitignore` agora inclui `/application/config.php`; o arquivo foi retirado somente do índice com `git rm --cached -- application/config.php`, mantendo a cópia local e suas credenciais. **Ignorar um arquivo já versionado não é suficiente: o primeiro pull dessa exclusão pode apagá-lo ou recusar a atualização se ele tiver alterações locais.** Os procedimentos abaixo preservam a configuração válida da escola. Não foram acessadas nem alteradas as credenciais do servidor da escola.

## Primeira atualização na escola: fazer uma vez

Comandos para Bash/Linux, executados na **raiz do projeto da escola**. O caminho real e as permissões do Apache precisam ser ajustados ao servidor. Faça em uma janela de manutenção, com o portal sem atender requisições durante a troca. Guarde também backup do banco e dos uploads antes da implantação.

1. Confira a branch e as alterações locais:

```bash
git branch --show-current
git status --short
```

A branch deve ser `production`. Preserve outras alterações de código antes de continuar; não use reset geral, clean ou comandos que descartem arquivos do servidor. Não faça commit da configuração com senha.

2. Preserve a configuração fora do repositório, sem exibir seu conteúdo:

```bash
umask 077
cetidam_backup_dir=$(mktemp -d "$HOME/cetidam-config.XXXXXX")
cp -- application/config.php "$cetidam_backup_dir/config.php"
chmod 600 "$cetidam_backup_dir/config.php"
php -l "$cetidam_backup_dir/config.php"
cmp -- application/config.php "$cetidam_backup_dir/config.php"
printf 'Backup privado: %s\n' "$cetidam_backup_dir"
```

Continue somente se todos os comandos funcionarem. `cmp` sem saída indica cópias idênticas. Guarde o caminho mostrado e mantenha esta mesma sessão de terminal para utilizar a variável nos próximos comandos.

3. Somente após confirmar o backup, libere o arquivo rastreado antigo, atualize e restaure a configuração válida da escola:

```bash
git restore --source=HEAD --staged --worktree -- application/config.php
git pull --ff-only origin production
cp -- "$cetidam_backup_dir/config.php" application/config.php
php -l application/config.php
```

Restaure a cópia com `cp` **mesmo se o pull falhar**; nesse caso, mantenha o portal em manutenção e resolva o erro antes de continuar. O restore atua somente no config.php e ocorre após o backup; durante essa etapa a versão antiga do repositório pode conter credenciais diferentes, por isso o portal deve permanecer sem atender requisições.

4. Após um pull bem-sucedido, confira:

```bash
git ls-files -- application/config.php
git check-ignore -- application/config.php
```

O primeiro comando deve ficar sem saída; o segundo deve mostrar `application/config.php`. Isso confirma que o arquivo válido existe apenas nessa instalação e está ignorado. Não use `git add -f` nele.

O PHP precisa conseguir ler o arquivo, mantendo escrita restrita ao responsável. Em Ubuntu com grupo de serviço `www-data`, um exemplo é:

```bash
sudo chgrp www-data application/config.php
chmod 640 application/config.php
```

Adapte o grupo ao usuário real do PHP/FPM. Não dê permissão 777. O backup continua privado e não deve ir para o repositório nem para a pasta pública.

## Banco e servidor antes de liberar o portal

- Use o nome do banco, host, usuário e senha da própria escola. Não copie config.php, maintenance.php, banco ou uploads desta máquina de desenvolvimento.
- Se o esquema da escola ainda for o antigo, execute `php scripts/migrar_banco.php --apply --admin` com backup privado configurado e uma conta autorizada a alterar tabelas. O comando faz backup antes de alterar o esquema e inclui a remoção de escola solicitada anteriormente. `--admin` utiliza `config/maintenance.php` quando disponível; esse arquivo privado retorna um array com `user` e `pass`. Não substitua a conta web limitada por root. Veja [banco e migrações](03_BANCO_DE_DADOS.md).
- Confirme PHP/extensões, DocumentRoot em `application/`, mod_rewrite e AllowOverride conforme `deploy/apache.conf.example`. Configure TLS e as permissões de `storage/` e uploads para o serviço PHP. O config.php fica no caminho original, com acesso HTTP bloqueado pelas regras do portal; nunca publique arquivos de backup ou cópias como config.php.bak.
- Valide `apachectl configtest`, a conexão pelo portal, login, permissões, listagem e cadastro/edição/exclusão com contas de teste autorizadas. A URL `/config.php` não deve mostrar ou executar a configuração diretamente. Confira também a tipografia, os avisos e o comportamento mobile antes de liberar o acesso.

O servidor da escola não está disponível neste ambiente; conexão, permissões e configuração Apache/TLS precisam ser validadas lá. Não importe o esquema vazio por cima de uma base existente.

A compatibilidade com a configuração original e essa transição foram verificadas por `python tests/config_git.py`: 11 verificações aprovadas com dois repositórios locais temporários e credenciais fictícias. A regressão do portal também passou após a mudança, com 1.675 verificações HTTP/banco; nenhum banco da escola foi acessado.

## Atualizações seguintes

Após essa transição, basta atualizar o código normalmente:

```bash
git pull --ff-only origin production
```

O config.php continuará sendo o da escola enquanto permanecer ignorado e fora do índice. A versão nova também ignora uploads novos; imagens já rastreadas em versões anteriores não foram removidas nesta publicação, evitando apagar mídias no primeiro pull. Faça backup dos uploads independentemente do Git.

Em uma instalação do zero, crie `application/config.php` a partir de `application/config.example.php` e preencha as credenciais localmente. A remoção do arquivo do índice não remove a senha antiga do histórico: a rotação já indicada na auditoria continua necessária.

## Referências

- [Git: gitignore e arquivos já rastreados](https://git-scm.com/docs/gitignore).
- [Git: rm --cached mantém o arquivo local](https://git-scm.com/docs/git-rm).
- [Git: pull e atualização fast-forward](https://git-scm.com/docs/git-pull).
