# Poppins e mensagens do portal

Alterações solicitadas em 11/11/2026: usar Poppins como fonte principal, explicar a perda das publicações na exclusão de uma conta e melhorar a apresentação das validações do servidor. Os componentes continuam na arquitetura MVC + DAO existente, sem bibliotecas novas de runtime.

## Fonte principal e variantes

A Poppins foi obtida pela API oficial do Google Fonts e armazenada em `application/assets/fonts/poppins/`. Foram incluídos os pesos 300, 400, 500, 600, 700 e 800, além do itálico 400. Os subconjuntos latin/latin-ext atendem aos caracteres usados em português; são 14 arquivos WOFF2, totalizando aproximadamente 93 KB. O navegador carrega as variantes necessárias para o texto exibido.

- Peso 400 no texto principal; 500 nos controles e navegação; 600/700 nos títulos e destaques. Os títulos existentes com peso alto podem utilizar 800; o subtítulo institucional usa 300.
- O itálico está disponível para ênfases semânticas, como `em` e `cite`.
- `font-display: swap` mantém o texto legível enquanto a fonte carrega, com uma fonte de sistema como alternativa.
- `application/assets/portal.css` define a família principal pelas variáveis do Bootstrap e reúne os estilos novos de feedback. Os layouts e estilos específicos já existentes foram preservados.
- Os dois templates carregam os mesmos estilos locais. A política CSP continua com `font-src 'self'`, sem liberar domínios externos.

Hospedar a fonte localmente permite seu uso na rede escolar sem depender de conexão com o Google em cada visita. Origem e SHA-256 dos arquivos estão em `application/assets/manifest.json`; a licença SIL OFL 1.1 está em `application/assets/fonts/poppins/OFL.txt`.

## Exclusão de usuário e publicações

O painel de usuários apresenta um aviso permanente explicando que excluir uma conta também apaga suas publicações. O antigo `confirm()` foi substituído por um único modal do Bootstrap, reutilizado pelas ações de desktop e celular.

O modal identifica o usuário e informa expressamente que serão excluídos a conta, todas as notícias, eventos e avisos cadastrados por ela, incluindo as imagens das notícias. Também explica que a ação não pode ser desfeita pelo portal. Os botões são “Cancelar” e “Excluir conta e publicações”.

Abrir o aviso não envia o formulário. O foco inicial fica em Cancelar; ao fechar, retorna ao botão de origem. Somente a confirmação envia o POST existente, com os mesmos campos e token CSRF. O nome é apresentado com `textContent`, sem interpretar HTML, e os botões são desabilitados durante o envio para evitar cliques repetidos. O retorno por histórico do navegador também limpa esse estado visual.

Não houve alteração na regra de exclusão do banco: ela já removia os registros vinculados em transação. A mudança torna essa consequência visível antes da decisão do usuário.

## Validação mais clara e visual

`Controller::campoPost()` continua validando os mesmos tipos e limites, mas passa a usar nomes legíveis, como “Título”, “Nome completo” e “Conteúdo da notícia”. A orientação distingue campo vazio, texto curto, texto longo e formato não aceito. As validações de contas também informam separadamente o problema no nome, matrícula, usuário, senha ou confirmação.

A view existente `error_inesperado.php` passou a apresentar um cartão com título, ícone grande, cores de aviso, explicação e ações de retorno. O controller informa o código HTTP à view para distinguir validação, duplicidade, acesso protegido, erro interno e desenvolvimento. O botão “Voltar e corrigir” utiliza o histórico do navegador, com a página inicial como alternativa. O retorno não faz um novo POST automaticamente.

Os alertas de sessão existentes ganharam título e ícone mais visível. Erros e avisos permanecem até o usuário fechá-los; somente mensagens de sucesso continuam desaparecendo automaticamente após quatro segundos. As mensagens continuam escapadas em HTML e os detalhes técnicos de exceções continuam restritos aos logs privados.

Foram utilizados os ícones do Bootstrap já presentes no projeto. Não foi necessária uma biblioteca de alertas, um novo conjunto de imagens ou uma view institucional nova.

## Código antigo retirado

- Removida a confirmação nativa antiga de exclusão de usuário e seus atributos `data-tipo` sem uso. O campo oculto `tipo_usuario`, utilizado pelo servidor, foi preservado.
- Removido do template limpo o script antigo de alternar senha, que procurava IDs ausentes nesse template. Os formulários existentes mantêm seus próprios controles de senha.
- Corrigida nesse template a referência externa de ícones, passando a utilizar o mesmo arquivo local do restante do portal.
- Os documentos de arquitetura, requisitos e testes foram atualizados para descrever o comportamento atual.

## Verificação

As suítes HTTP verificam a disponibilidade das fontes WOFF2, as variantes, os recursos locais, a presença do aviso completo, os atributos de acessibilidade do modal e as orientações de validação, além de toda a matriz de segurança já existente.

O teste `tests/validar_confirmacao_usuario.mjs` executa o bloco real de eventos com um DOM simulado. Verifica abrir, cancelar, retornar o foco, tratar o nome como texto, escolher o formulário correto e impedir envios repetidos. Ele utiliza somente módulos nativos do Node e não controla um navegador. Os resultados e as limitações da inspeção visual estão em [Testes e pendências](05_TESTES_E_PENDENCIAS.md).

Os testes foram ajustados para limpar somente as imagens conhecidas que eles próprios criaram, com nomes aleatórios, preservando uploads do usuário que possam ocorrer durante o trabalho.

Na preparação para publicação, `.gitattributes` passou a preservar os bytes dos assets ao alternar Windows/Linux, mantendo os hashes do manifesto. A licença oficial da Poppins permanece intacta, inclusive seus espaços originais.

Resultado da rodada de tipografia: 1.576 verificações principais e 98 complementares aprovadas, além de 8 verificações dos eventos de confirmação; 77 arquivos PHP e 19 blocos JavaScript sem erro de sintaxe. A regressão posterior da configuração e os totais atuais estão em [Testes e pendências](05_TESTES_E_PENDENCIAS.md).

## Referências

- [Poppins no Google Fonts](https://fonts.google.com/specimen/Poppins).
- [API CSS2 do Google Fonts](https://developers.google.com/fonts/docs/css2).
- [Licença oficial da Poppins](https://github.com/google/fonts/blob/main/ofl/poppins/OFL.txt).
- [Modal do Bootstrap](https://getbootstrap.com/docs/5.3/components/modal/).
