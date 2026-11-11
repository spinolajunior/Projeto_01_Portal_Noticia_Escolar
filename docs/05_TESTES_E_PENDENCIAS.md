# Testes e pendências

Validação em 11/11/2026, com PHP 8.5.8 e MySQL 9.7.1 locais. O teste usa o mesmo código da aplicação e um banco separado, criado a partir do esquema real, preenchido somente com dados fictícios. A conta de banco usada pelo servidor de teste tem apenas SELECT/INSERT/UPDATE/DELETE. Credenciais e registros reais da escola não são usados como contas de teste.

## Resultado executado

| Verificação | Resultado |
| --- | --- |
| Matriz HTTP/segurança principal | 1.577 verificações aprovadas; zero falhas na última rodada completa, após voltar ao config.php privado. |
| Casos complementares | 98 verificações aprovadas; zero falhas após as melhorias de tipografia, avisos e validação. |
| Sintaxe PHP | 78 arquivos de código/exemplo/teste sem erro de sintaxe na última revisão. |
| Configuração e primeira atualização pelo Git | 11 verificações aprovadas em diretórios temporários: config original, host:porta, backup/restauração, saída do índice, ignore e preservação no pull seguinte. Sem banco real. |
| Integridade dos assets no Git | 23 arquivos do manifesto conferidos por SHA-256 no conteúdo preparado para publicação. |
| JavaScript das views | 19 blocos ativos compilados sem erro. Foram retiradas funções sem uso e o script antigo de senha do template limpo. Não equivale a execução visual em navegador. |
| Eventos da confirmação de exclusão | 8 verificações aprovadas com DOM simulado: abertura, cancelamento, foco, envio correto e proteção contra cliques repetidos. |
| Maiúsculas/minúsculas de imports | Nenhuma divergência de caminho nos módulos existentes; Contato/Endereço permanecem reservados e sem seus modelos. |
| Dados originais | Contagens e resumos preservados durante os testes. A exclusão expressamente solicitada dos 20 registros de escola preservou os dados das outras 8 tabelas. |
| Privilégios da aplicação real | Conta local DML verificada, sem ALL PRIVILEGES ou GRANT OPTION. |
| Backup | Backup privado criado antes da exclusão; restauração sintética validada nas 8 tabelas atuais, sem recriar escola, em outra base temporária. |

Total das suítes HTTP/banco atuais: **1.675 verificações aprovadas**, além das 8 verificações dos eventos de confirmação e das 11 verificações de configuração/Git. Foram retirados os testes do módulo Escola e incluídas verificações de sua ausência, dos 14 arquivos de fonte WOFF2, dos avisos e das mensagens específicas dos campos. A matriz principal e os casos complementares foram repetidos após a mudança para config.php privado e passaram integralmente. A proteção HTTP inclui config.php, config.example.php e bootstrap.php. Os relatórios HTTP detalhados ficam em `tests/.artifacts/results.json` e `tests/.artifacts/extra-results.json`, ignorados pelo Git e recriados pelos testes.

A primeira rodada completa encontrou 44 falhas, concentradas em import ausente nos formulários de edição e nas rotas de desenvolvimento. Esses pontos foram corrigidos e a matriz completa foi repetida. Os testes complementares validaram também a ordenação pública e a ocultação das ações proibidas no menu de aluno.

## Cobertura relacionada ao DEF

| Casos do DEF | Evidência executada |
| --- | --- |
| CT1–CT5 | Login válido/inválido, conta bloqueada, retorno para rota interna e acesso conforme perfil. |
| CT6–CT7 | Perfil usa sessão, imagem padrão disponível, senha e usuário vazios preservados. |
| CT8–CT9 | 12 notícias ativas geram quatro páginas; paginação inválida é tratada; notícia inativa/inexistente retorna 404. |
| CT10–CT11 | Datas passadas/impossíveis, arquivos inválidos, PHP disfarçado, PNG truncado, tamanho maior que 10 MB e dimensões acima de 16 MP recusados. JPG/PNG/WebP válidos aceitos e reprocessados como JPEG. |
| CT12–CT14 | Professor cadastra aluno; cargos forçados são recusados; duplicidade e campos inválidos não deixam credenciais órfãs. |
| CT15–CT16 | Exclusão/bloqueio da própria conta ou de superior recusados; conta subordinada pode ser bloqueada/reativada; sessão aberta é revogada. |
| CT17–CT18 | SQL injection no login não autentica; scripts em título/conteúdo aparecem escapados como texto. |
| CT19–CT22 | CSRF ausente/incorreto/de outra sessão é recusado; IDs e campos sensíveis forjados não ampliam acesso; autoria/hierarquia verificadas em GET e POST. |
| CT23–CT24 | ID de sessão renovado e redirecionamentos externos/protocol-relative/encodados recusados. |
| CT25–CT27 | Configuração, logs, código interno e diretórios privados retornam 404; cabeçalhos presentes; falha real provocada por trigger gera resposta genérica e log privado. |

Também foram testados: matriz entre autores/alunos/professores/níveis 2–4 e pares; preservação de autoria; exclusão de publicações; exclusão transacional de conta com notícias/eventos/avisos; limpeza de imagem após exclusão/substituição; preservação da foto do gestor; rollback do perfil/credencial/foto em falha do banco; migração de senha SHA-256; expiração por inatividade; troca de senha revogando outras sessões; logout; avisos/eventos sem notícias; CSRF nos formulários renderizados; ordenação pública; regras de cargos oferecidos nos formulários; ausência da tabela escola no banco ativo, no banco de teste e na restauração do esquema atual.

Na atualização de tipografia e feedback, também foram verificados: carregamento local da Poppins e suas variantes, orientação para campos vazios/curtos/longos, mensagens específicas de cadastro, permanência dos erros até serem fechados, conteúdo explícito do aviso de exclusão e atributos de acessibilidade do modal.

## Reproduzir

Executar na raiz do projeto com PHP/Python disponíveis e a configuração privada de manutenção autorizada para criar bases/usuários de teste:

```powershell
python tests/run.py --keep
python tests/extra.py
php tests/fixture.php cleanup
python tests/config_git.py
```

O primeiro comando cria uma base com prefixo aleatório `cetidam_test_` e uma conta DML exclusiva. `--keep` mantém essa base para os testes complementares; o último comando remove somente essa base/conta de teste identificada pelo arquivo privado de fixture. Não executa DROP sobre o banco do portal. Rodar apenas `python tests/run.py` já executa a matriz principal e limpa sua base ao terminar.

Se houver Node disponível, a compilação de JavaScript pode ser repetida com:

```text
node tests/validar_javascript.mjs
node tests/validar_confirmacao_usuario.mjs
```

Não foi adicionado pacote de teste, dependência de runtime ou framework ao portal. Os testes provocam falhas controladas somente na base fictícia e criam/removem temporariamente suas próprias imagens de teste.

## Limitações e ações pendentes

1. **Inspeção visual pendente:** o host de automação de navegador continua indisponível. Na rodada de tipografia, a janela nativa do Chrome identificou o portal, mas foi minimizada durante a preparação da prévia; a verificação visual não foi concluída. Foram verificados sintaxe, HTML gerado, assets, fluxos HTTP e eventos do modal com DOM simulado. Antes de publicar, conferir visualmente menu mobile, carrosséis, pré-visualização de fotos, confirmações e responsividade no navegador.
2. **Apache/Ubuntu de destino não disponíveis neste ambiente:** as regras `.htaccess`, configuração de TLS/FPM e permissões reais no servidor Linux precisam ser aplicadas e verificadas nele. Há exemplos em `deploy/`; o router PHP foi testado localmente. Não foi simulada uma implantação pública.
3. **HTTPS:** HTTP na rede interna mantém risco de captura de credenciais. Configurar TLS e redirecionamento antes da implantação definitiva; não considerar intranet substituto de criptografia. HSTS/Secure funcionam quando o servidor sinaliza HTTPS corretamente.
4. **Credencial root no histórico:** rotacionar a senha administrativa anterior e avaliar cópias do repositório. A aplicação já usa uma credencial nova limitada; a senha root não foi alterada para evitar interromper outros usos dessa conta.
5. **Matrícula duplicada legada:** confirmar e corrigir o grupo repetido de administradores; depois repetir a migração para criar seu índice. Não houve exclusão automática de registros. Registros/tipos de teste preexistentes também precisam de revisão do responsável.
6. **Hashes antigos:** 27 hashes SHA-256 ainda existiam após a revisão. A migração acontece em login válido. Contas que não acessarem precisam de troca de senha supervisionada quando a escola concluir a transição.
7. **Tentativas de login:** não há limitação automática por IP/usuário nem bloqueio por tentativas, seguindo a restrição desta versão do DEF. Avaliar limitação no servidor antes de exposição pública.
8. **Operação contínua:** agendar backup fora do projeto, incluir uploads, definir retenção/acesso dos logs e backups, configurar alertas e testar restauração periodicamente. O script e a restauração sintética foram testados; o agendamento/monitoramento do servidor da escola não foi configurado.
9. **PHP local:** a instalação `C:\php\php.ini` carrega mysqli duas vezes e emite aviso de inicialização. A extensão usada pelo portal é PDO MySQL; os testes passaram. Essa configuração global externa não foi modificada.
10. **Escopo reservado:** Contato/Endereço possuem esboços de DAO sem modelos/tabelas/rotas; CETIDAM e Contato seguem apenas com a indicação de desenvolvimento, conforme solicitado.

A revisão reduz os riscos identificados; não constitui certificação OWASP, pentest externo nem garantia de ausência de vulnerabilidades. Reavaliar o sistema quando alterar permissões, banco, dependências ou forma de implantação.

## Referências técnicas verificadas

- [OWASP Top 10:2025](https://top10.owasp.org/2025/).
- [OWASP: prevenção de CSRF](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html).
- [OWASP: gestão de sessão](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html).
- [PHP: password_hash](https://www.php.net/manual/en/function.password-hash.php) e [funções de senha](https://www.php.net/manual/en/ref.password.php).
- [Apache: regras de rewrite e flag END](https://httpd.apache.org/docs/2.4/rewrite/flags.html).
- [Bootstrap: JavaScript e sanitização](https://getbootstrap.com/docs/5.3/getting-started/javascript/) e [avisos oficiais de segurança](https://github.com/twbs/bootstrap/security/advisories).

As versões Bootstrap 5.3.3/ícones 1.11.3 foram preservadas. A Poppins foi acrescentada como fonte principal por solicitação do responsável, com arquivos locais. O manifesto registra as origens e hashes, sem afirmar que versões fixas dispensam atualização ou acompanhamento de avisos futuros.
