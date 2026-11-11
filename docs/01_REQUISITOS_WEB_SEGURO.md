# Requisitos para um portal funcional e seguro

Revisão iniciada em 11/11/2026. Escopo: Portal CETIDAM existente, sem ampliar funcionalidades institucionais. O DEF fornecido é referência de negócio; as instruções do solicitante prevalecem. CETIDAM e Contato terão somente indicação de desenvolvimento.

## Funcionamento e manutenção

- Rotas e métodos HTTP definidos; GET consulta, POST altera. Respostas 400/403/404/405/500 coerentes, sem páginas em branco nem detalhes internos.
- Preservar MVC + DAO, PHP/PDO e Bootstrap. Reaproveitar métodos existentes e concentrar somente regras realmente compartilhadas.
- Navegação, formulários com rótulos, mensagens em português, confirmação de exclusão e apresentação responsiva já existentes.
- Tipografia principal Poppins com variantes locais; mensagens de validação com ícone, orientação clara e ação de retorno. A confirmação de exclusão de conta explicita a remoção de todas as publicações vinculadas.
- Validação no servidor, inclusive sem JavaScript: presença, tipo, comprimento, datas, IDs, arquivos, unicidade e relacionamentos.
- Área pública apresenta apenas notícias/avisos ativos e eventos a partir da data atual; notícias inativas também são inacessíveis pelo ID.
- Permissões por tipo e nível, no servidor, em listagens e ações. Perfil próprio usa exclusivamente a sessão. Hierarquia não permite gerir pares, superiores, a própria conta por exclusão/bloqueio ou administrador técnico.
- Operações relacionadas usam transação; arquivos novos são removidos em falhas e arquivos antigos somente após confirmação no banco.
- Configuração reproduzível, segredos fora do Git e preferencialmente fora da área pública, dependências locais com versão fixa, logs privados e testes de regressão. Neste projeto, por solicitação do responsável, `application/config.php` permanece na localização original e tem acesso HTTP bloqueado pelo router e pelas regras Apache.
- Implantação precisa de HTTPS, usuário de banco com privilégios mínimos, backup externo com restauração testada, atualização dos componentes e monitoramento. Código sozinho não comprova essas condições operacionais.

## OWASP Top 10:2025 aplicado

| Risco | Critério verificável |
| --- | --- |
| A01 Controle de acesso quebrado | Autenticar antes de operações restritas; validar autoria/hierarquia pelo registro real; CSRF; impedir acesso a arquivos privados e caminhos arbitrários. |
| A02 Configuração insegura | Cookies HttpOnly/SameSite/Secure em HTTPS; cabeçalhos; servidor sem listagem; erros genéricos; configurações/logs inacessíveis pela URL. |
| A03 Falhas na cadeia de suprimentos | Dependências fixas e identificadas; assets locais; revisão periódica de avisos do fornecedor; nenhum download executável durante requisições. |
| A04 Falhas criptográficas | password_hash/password_verify, migração gradual de SHA-256, senha fora de cookies/logs, TLS e segredos privados. |
| A05 Injeção | PDO com parâmetros reais, sem concatenar entradas; escapar saída HTML; tratar notícia como texto; validar atributos de URL. |
| A06 Projeto inseguro | Matriz explícita de permissões; validação independente do navegador; transações e tratamento de falhas; evitar recursos sem regra de negócio. |
| A07 Falhas de autenticação | Renovar sessão no login, expirar por inatividade, revogar contas bloqueadas, mensagem genérica para credenciais erradas e logout completo. Registrar ausência de limitação de tentativas prevista no DEF. |
| A08 Falhas de integridade de software/dados | Reprocessar imagens, nomes aleatórios, impedir execução de uploads, chaves e restrições no banco, mudanças documentadas e revisáveis. |
| A09 Falhas de logs e alertas | Auditar login e alterações com data/autor/IP/resultado, sem senhas; acesso técnico restrito; monitoramento/alertas operacionais documentados. |
| A10 Tratamento inadequado de exceções | Falhar sem ampliar acesso, reverter transações, compensar arquivos e registrar erro privado; resposta pública sem SQL/stack trace. |

O Top 10 é uma referência de riscos, não certificação de segurança nem substituto de testes completos.

## Regras de negócio adotadas

| Perfil | Publicações | Contas | Próprio perfil |
| --- | --- | --- | --- |
| Aluno 0 | Consulta pública | Nenhuma | Consulta |
| Aluno administrador 1 | Somente próprias | Nenhuma; sem central administrativa | Consulta |
| Professor 1 | Próprias e de alunos administradores | Alunos 0 | Edita |
| Administração 2 | Próprias e níveis inferiores | Níveis 0 e 1 | Edita |
| Direção 3 | Próprias e níveis inferiores | Níveis 0 a 2 | Edita |
| Sistema 4 | Todas | Níveis 0 a 3 | Edita |

Conflito RF2.2 × RF3.7: adota-se a regra mais restritiva RF3.7 (alunos não editam o próprio perfil). Professor pode gerir publicações de aluno administrador pela exceção explícita RF2.3. Cadastro de aluno administrador e do responsável técnico continua reservado ao banco. Senhas iguais entre contas passam a ser permitidas com hashes aleatórios: aplica-se RNF19.4 em substituição à antiga unicidade de RF7.5. Sem cadastro público, recuperação de senha ou bloqueio automático por tentativas nesta versão.

## Fontes verificadas

- [OWASP Top 10:2025](https://top10.owasp.org/2025/), versão vigente consultada em 11/11/2026.
- DEF_Portal_CETIDAM_ABNT.md fornecido pelo solicitante: RF1–RF10, RNF e CT1–CT27.
