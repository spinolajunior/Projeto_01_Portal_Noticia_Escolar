# Auditoria do Portal CETIDAM

Data inicial: 11/11/2026. Referência de negócio: DEF fornecido. Inspeção inicial feita antes das correções abaixo. Este registro será complementado com resultados executados e pendências reais.

## Arquitetura e estilo encontrados

`index.php` inicializa configuração/sessão e chama `routes.php`, um switch simples. Controllers usam métodos estáticos; Models tipados delegam ao DAO; DAOs usam PDO; `View` monta o conteúdo e o template com buffer. A interface já contém Bootstrap, validações JavaScript, tabelas desktop e cards mobile. Preservar esse fluxo, nomes e aparência. Corrigir funções existentes e compartilhar apenas autorização, validação, CSRF, arquivos e persistência que se repetem.

## Falhas constatadas no estado inicial

| ID | Gravidade / OWASP 2025 | Evidência e impacto | Correção prevista |
| --- | --- | --- | --- |
| F01 | Crítica / A01 | `delete()` de notícia/evento/aviso aceita GET sem autenticação; acesso de contas também sem proteção. | POST, sessão, CSRF e autorização por registro. |
| F02 | Crítica / A01/A06 | Edições usam IDs do formulário, inclusive credencial independente do perfil; falta hierarquia/autoria. | Recuperar alvo no banco e derivar credencial/autor no servidor. |
| F03 | Crítica / A01 | Cadastro aceita tipo arbitrário; aluno administrador acessa recursos administrativos; painéis retornam contas/publicações fora do escopo. | Matriz de permissões em listagens, telas e mutações. |
| F04 | Alta / A07/A04 | Login grava senha em cookie por 30 dias; hash fica na sessão; não regenera ID, não expira por inatividade nem revoga sessão bloqueada. | Remover cookies legados/senha na sessão, regenerar ID, timeout e revalidar conta. |
| F05 | Alta / A01 | Nenhum formulário possui proteção CSRF; logout por GET. | Token aleatório por sessão com `hash_equals`; logout POST. |
| F06 | Alta / A04 | SHA2 no MySQL recebe senha em texto; índice UNIQUE de senha revela igualdade e impede senhas iguais. | Hash de senha no PHP, compatibilidade de login legado, migração de índice. |
| F07 | Alta / A02/A09 | Configuração com acesso root está rastreada pelo Git, logs na pasta pública, sem proteção de arquivos. | Segredos privados, usuário DML, regras Apache/router; documentar credencial histórica para rotação. |
| F08 | Alta / A01 | `next_url` do login é usado diretamente no Location. | Permitir somente rota interna validada. |
| F09 | Alta / A01 | Notícia inativa pode ser lida diretamente pelo ID. | Filtrar status também na consulta individual. |
| F10 | Alta / A10 | Conexão captura exceção e continua com PDO nulo; outros pontos imprimem SQL/erro e var_dump. | Exceções tratadas na entrada; resposta genérica e log privado. |
| F11 | Alta / A06 | Quase toda validação está apenas no JavaScript; POST arrays/IDs/datas/títulos inválidos alcançam banco. | Validar tipos, limites do DEF/banco, enums, datas e campos obrigatórios no PHP. |
| F12 | Alta / A08/A10 | Notícia inserida antes do upload; falha parcial pode reportar sucesso; foto de outro usuário apaga a foto do gestor. | Upload e transações com limpeza compensatória; remover somente arquivo do alvo após commit. |
| F13 | Alta / A01/A08 | `unlink(BASE_DIR + caminho do banco)` sem restrição; foto padrão pode ser apagada. | Restringir remoção a arquivos gerados dentro dos diretórios de upload, nunca `user_def.jpg`. |
| F14 | Média / A03/A02 | Bootstrap/ícones de CDN; sem CSP/cabeçalhos de proteção. | Assets locais fixos, CSP com nonce para scripts existentes e cabeçalhos. |
| F15 | Média / A09 | Logger não grava, lê arquivo fixo de outubro e painel visível a qualquer autenticado. | Registro estruturado mensal privado e painel somente nível técnico 4. |
| F16 | Média / A10 | Caminhos/namespaces com maiúsculas divergentes funcionam no Windows e falham no Linux; autoload não converte barras. | Padronizar arquivos/imports e traduzir namespaces em caminhos. |
| F17 | Média / funcional | Rota de exclusão de usuário comentada; método vazio; rota /avisos aponta para arquivo inexistente; contato duplicado; desconhecidas redirecionam à Home. | Concluir exclusão existente com integridade; reaproveitar Home; 404 real; página simples de desenvolvimento. |
| F18 | Média / funcional | Aviso administrativo grava ID na coluna de aluno; páginas/validações têm limites incompatíveis com o banco; data passada e avisos inativos sem filtro confiável. | Corrigir autoria, filtros e limites coerentes. |
| F19 | Média / funcional | Home com notícias vazias perde eventos/avisos; paginação inválida mostra página incoerente. | Montar sempre o mesmo model, limitar página e preservar sidebar. |
| F20 | Média / A06 | Banco não garante matrícula única; tipos administrativos e séries têm dados de teste preexistentes, inclusive níveis 5–10 não previstos. | Inspecionar duplicidades, proteger novos cadastros/edições e negar níveis fora de 0–4; não apagar registros existentes. |
| F21 | Baixa / apresentação | Nome aceita 150 caracteres mas banco limita 100; mensagens e indicadores de campos não correspondem a algumas regras do DEF. | Alinhar mensagens, limites e campos opcionais sem reformular layout. |
| F22 | Média / manutenção | Controllers legados apontam para views/propriedades inexistentes e método de foto ausente; EscolaDAO usa colunas que não existem. | Reaproveitar UsuarioController e alinhar EscolaDAO ao esquema, sem novas views. |
| F23 | Escopo reservado | ContatoDAO/EnderecoDAO dependem de modelos e tabelas ausentes; EnderecoDAO também contém erro no nome da tabela. | Corrigir a grafia e manter módulos sem rota; implementação institucional fora do escopo solicitado. |

## Dados e ambiente observados

PHP CLI 8.5.8; GD, EXIF, mbstring, PDO MySQL e fileinfo disponíveis. MySQL 9.7.1 em serviço local. Há um aviso de configuração do PHP: extensão mysqli carregada duas vezes. Isso pertence ao `C:\php\php.ini`, não ao código do portal.

Banco com 9 tabelas InnoDB, charset utf8mb4 e collation utf8mb4_bin (usuários diferenciam maiúsculas). Relações: credenciais → aluno/administrador; tipo_adm → ambos os perfis; serie → aluno; aluno/administrador → notícia/evento/aviso. FKs atuais usam RESTRICT, sem exclusão em cascata. Não foram consultadas nem reproduzidas senhas em documentação.

Quantidades iniciais: 28 credenciais, 11 alunos, 17 administradores, 2 notícias, 11 avisos, 0 eventos, 17 séries, 14 tipos administrativos e 20 registros de escola. Dados de teste já existiam antes desta revisão. Não serão removidos automaticamente.

Atualização posterior: o responsável solicitou expressamente a retirada da tabela `escola`. Ela e seus 20 registros foram removidos com backup prévio; os dados das demais tabelas foram preservados. O módulo e seus testes específicos foram retirados. A contagem atual passou para 8 tabelas; os números acima permanecem como registro da inspeção inicial.

Arquivos de modelagem/SQL marcados como excluídos no Git antes do trabalho serão mantidos nesse estado. Nenhum dado institucional será inventado para CETIDAM ou Contato.

## Situação após as correções

F01–F06, F08–F19, F21 e F22 foram corrigidas no código e verificadas por testes HTTP, banco, análise estática e/ou sintaxe, conforme o relatório de testes. As funções repetidas de edição e sessão foram consolidadas nos componentes existentes.

F07 foi corrigida no código atual e no acesso local da aplicação: configuração privada, conta DML, logs privados e bloqueio de URLs de arquivos internos. Por solicitação posterior do responsável, as credenciais permanecem diretamente em application/config.php, retirado do controle do Git; somente config.example.php é distribuído. A primeira atualização da escola exige preservar/restaurar seu arquivo, conforme o guia de publicação. A senha root que já estava no histórico do Git continua exigindo rotação operacional; o histórico não foi reescrito.

F20 permanece parcialmente pendente por dados existentes: um grupo de matrículas duplicadas de administradores impede criar seu índice UNIQUE. Não foram apagados registros reais. As novas alterações pela aplicação são validadas; os tipos fora dos níveis aceitos não recebem acesso. O índice de matrícula de aluno foi aplicado.

F23 permanece como módulo reservado sem rota, conforme a instrução de não construir a área de Contato. Não é apresentado como funcionalidade concluída.

Os controles de Apache/TLS, a inspeção visual em navegador, a agenda de backup e o monitoramento do servidor de destino têm as limitações descritas em [Testes e pendências](05_TESTES_E_PENDENCIAS.md). O detalhamento de cada mudança e de seu motivo está no [guia das alterações](04_GUIA_DAS_ALTERACOES.md).
