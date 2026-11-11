# Entendendo as alterações

O projeto mantém o caminho de execução existente:

`index.php → bootstrap.php/config.php → routes.php → Controller → Model → DAO → MySQL → View → template`

Não foi introduzido framework, serviço externo, cadastro público ou recuperação de senha. As correções ficaram nos componentes que já tinham essas responsabilidades.

## Entrada e rotas

Em [index.php](../application/index.php), a sessão passa a ter cookie HttpOnly/SameSite, renovação no login, expiração e armazenamento privado. A entrada também revalida a conta e o cargo a cada requisição: bloquear uma conta atinge uma sessão que já estava aberta. A troca de senha invalida outras sessões por uma marca HMAC vinculada à sessão, sem guardar o hash original nela.

Os erros são tratados nesse ponto, com resposta genérica e informações técnicas em log privado. O código deixa de continuar com conexão PDO nula. A aplicação envia CSP, proteção contra framing/sniffing, política de referência e HSTS quando recebe HTTPS diretamente do servidor. Um proxy TLS precisa encaminhar HTTPS pela configuração confiável do servidor; cabeçalhos arbitrários do cliente não são usados para decidir isso.

Em [routes.php](../application/routes.php), as ações de alteração aceitam POST e exigem CSRF; exclusão e logout não aceitam GET. URLs desconhecidas retornam 404. `/avisos` reaproveita a Home. CETIDAM/Contato utilizam a mensagem genérica de desenvolvimento, sem dados institucionais inventados.

## Autorização e validação compartilhadas

Em [Controller.php](../application/controller/Controller.php), ficam somente regras que vários fluxos precisam:

- `verificarAcesso` e `verificarGestor`: acesso por nível e tipo; aluno administrador não vira gestor de contas por ter nível 1.
- `nivelUsuario`, `podeGerenciarUsuario`, `podeGerenciarPublicacao` e `autorizarPublicacao`: escopo do registro real, usado tanto para listar como para alterar/excluir.
- `campoPost`, `idRequisicao` e `validarData`: tipos escalares, limites, datas e IDs validados no servidor, mesmo sem JavaScript.
- `validarCsrf`: comparação do token da sessão com hash_equals.
- `adicionarFoto`: função de upload existente, mantida e reforçada; reprocessa JPG/PNG/WebP, limita a 10 MB/16 MP e reduz a maior dimensão a 1.920 px em notícia ou 1.024 px em perfil.
- `removerFoto`: apaga somente arquivos gerados dentro do upload; preserva o arquivo padrão e impede caminhos arbitrários.
- `atualizarSession`: reaproveitada pelo login e pela atualização do perfil, eliminando duas inicializações divergentes de permissões.

## Login e contas

[LoginController.php](../application/controller/LoginController.php) não grava mais senha em cookie e elimina cookies antigos quando encontrados. A opção “Lembrar-me” foi retirada porque sua implementação guardava a senha em texto; não foi criado um novo mecanismo de autenticação persistente.

O retorno pós-login aceita somente caminhos internos. Conta bloqueada só recebe mensagem específica depois da senha correta. O último acesso é atualizado somente após validar credenciais de conta ativa.

Em [UsuarioController.php](../application/controller/UsuarioController.php), os três fluxos de edição reutilizam a mesma rotina, sem repetir upload, validação e limpeza de fotos. O perfil próprio sempre usa IDs da sessão. A edição de subordinados deriva a credencial do usuário encontrado no banco, ignorando o campo oculto forjado.

Nome, matrícula, usuário, senha, nascimento e série são os únicos dados de edição aceitos. Cargo, nível, status e autoria não entram por atribuição em massa. Senhas e usuários em branco são preservados. A foto antiga do usuário alterado é apagada somente depois do sucesso da transação; a foto do gestor fica intacta.

O cadastro usa os tipos institucionais existentes e inferiores ao gestor. Cadastro técnico nível 4 e promoção de aluno administrador continuam fora da interface. A listagem, bloqueio e exclusão utilizam a mesma regra de hierarquia; exclusão/bloqueio da própria conta e da conta técnica são recusados.

## Persistência e publicações

Em [DAO.php](../application/DAOs/DAO.php), a conexão usa parâmetros reais, utf8mb4 e exceções. Foi acrescentada somente a possibilidade de reutilizar o mesmo PDO dentro da transação de atualização de conta.

[CredenciaisDAO.php](../application/DAOs/credenciais/CredenciaisDAO.php) verifica a senha no PHP, migra hashes legados e preserva credenciais vazias na edição. [UsuarioDAO.php](../application/DAOs/usuario/UsuarioDAO.php) mantém perfil, credencial e foto na mesma transação, reutilizando o DAO de credenciais. A exclusão remove publicações/perfil/credencial na ordem das FKs, com rollback em falhas.

Notícia, evento e aviso continuam em seus controllers/models/DAOs separados. Todos verificam autor/hierarquia antes de abrir formulários ou alterar registros. A autoria vem da sessão. O aviso de administrador agora usa a coluna correta. Notícia grava texto e imagem na mesma instrução SQL; um upload novo é removido se o banco recusar a gravação. A imagem anterior é removida após atualização/exclusão bem-sucedida.

O status do aviso é vinculado no PDO como inteiro, inclusive quando desativado, evitando converter `false` em uma string vazia inválida para o MySQL.

A Home filtra notícias/avisos ativos e eventos atuais/futuros, preserva eventos/avisos sem notícias e mantém os destaques globais mais recentes. Eventos são ordenados pela data mais próxima e avisos pelo ID mais recente. A notícia individual também exige status ativo.

O módulo Escola e sua tabela foram removidos posteriormente por solicitação do responsável, incluindo model, DAO e controller. Os controllers legados de Aluno/Administrador passaram a encaminhar para UsuarioController, evitando formulários ausentes. Os módulos ainda sem rota não receberam novas funcionalidades.

## Views, arquivos e logs

As views conservaram a organização dos componentes responsivos. As mudanças incluem campos CSRF, formulários POST nos botões de exclusão/logout, nonces, limites e indicação correta das ações permitidas. O link de edição deixa de aparecer no perfil de aluno pela regra restritiva adotada para RF3.7. Na atualização de tipografia e avisos, a Poppins passou a ser a fonte principal e os componentes existentes de feedback foram aprimorados.

Bootstrap 5.3.3, ícones 1.11.3 e os arquivos da Poppins ficam em `application/assets/`, com licenças e manifesto de origem/SHA-256. A tipografia e os novos avisos são definidos no arquivo global `portal.css`, mantendo os estilos específicos das páginas. Scripts recebem nonce; os fallbacks de imagens usam listeners em vez de onerror inline. Estilos de atributo foram preservados sob `style-src-attr 'unsafe-inline'`, uma concessão explícita ao layout atual; scripts inline sem nonce continuam bloqueados.

A exclusão de usuário utiliza um modal do Bootstrap que explica a remoção de notícias, eventos, avisos e imagens vinculadas. As mensagens de validação reaproveitam a view de erro existente, com ícones, orientações específicas e retorno ao formulário. Erros e avisos permanecem visíveis até serem fechados. As decisões e os trechos antigos retirados estão em [Poppins e avisos](07_TIPOGRAFIA_E_AVISOS.md).

O autoload converte barras de namespace e os nomes `View.php`/`limpo.php` foram normalizados para Linux. Arquivos privados não são servidos pelo router de desenvolvimento nem pelas regras de rewrite do Apache previstas em `application/.htaccess`. Uploads não podem executar PHP.

[LogController.php](../application/controller/LogController.php) grava eventos JSON por mês fora da pasta pública, sem senha/hash. O painel é exclusivo do nível 4 e limita a exibição aos 1.000 registros recentes de até 1 MB do mês atual. O log antigo foi preservado em `storage/logs/`.

## Executar e manter

```powershell
php -S 127.0.0.1:8080 -t application router.php
php scripts/inspecionar_banco.php
```

O banco continua configurado diretamente em `application/config.php`, ignorado pelo Git, conforme solicitado. Em uma instalação nova, copie `application/config.example.php` para esse caminho e preencha uma conta DML do banco. `bootstrap.php` concentra a inicialização sem segredos e mantém compatibilidade com BASE_DIR, VIEW e host:porta da configuração original. A aplicação e as ferramentas CLI reutilizam essa mesma entrada. Foi retirado o carregamento de config/local.php e a ferramenta que gerava esse arquivo, evitando dois locais para as credenciais da aplicação.

Na escola, preserve o config.php existente antes da primeira atualização que deixa de versioná-lo. O [guia de publicação](08_PUBLICACAO_E_CONFIGURACAO.md) explica essa transição e os pulls seguintes. Não copie as credenciais desta máquina para o servidor.

Os exemplos de Apache/PHP em `deploy/` precisam ser aplicados e validados no servidor de destino. Para backup automático, agende a execução de `php scripts/migrar_banco.php --backup-only` por cron/Agendador, escolhendo `CETIDAM_BACKUP_DIR` privado. Essa agenda não foi instalada em um servidor Ubuntu a partir desta máquina. Inclua também os diretórios de uploads na rotina de backup.
