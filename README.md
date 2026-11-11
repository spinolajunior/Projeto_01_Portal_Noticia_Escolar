# Portal CETIDAM

Aplicação PHP com MVC + DAO, MySQL/PDO e Bootstrap. Revisão funcional e de segurança realizada em 11/11/2026, preservando a arquitetura e as views existentes.

## Documentação da revisão

1. [Requisitos de um portal funcional e seguro, com OWASP Top 10:2025](docs/01_REQUISITOS_WEB_SEGURO.md)
2. [Falhas encontradas e situação das correções](docs/02_AUDITORIA_E_CORRECOES.md)
3. [Banco de dados, credenciais e migração](docs/03_BANCO_DE_DADOS.md)
4. [Como entender as alterações e executar o projeto](docs/04_GUIA_DAS_ALTERACOES.md)
5. [Testes executados e pendências](docs/05_TESTES_E_PENDENCIAS.md)
6. [Remoção da tabela escola e de seu módulo](docs/06_REMOCAO_ESCOLA.md)
7. [Poppins, confirmação de exclusão e mensagens amigáveis](docs/07_TIPOGRAFIA_E_AVISOS.md)
8. [Publicação na branch production e preservação do config.php da escola](docs/08_PUBLICACAO_E_CONFIGURACAO.md)

## Executar localmente

Requer PHP 8.5, PDO MySQL, mbstring, fileinfo, GD e MySQL. EXIF é utilizado quando disponível. O banco continua configurado em `application/config.php`, como no projeto original; esse arquivo é privado e ignorado pelo Git. Para uma instalação nova, copie `application/config.example.php` para `application/config.php` e preencha os dados de conexão. Na primeira atualização da escola, siga o [procedimento de preservação](docs/08_PUBLICACAO_E_CONFIGURACAO.md) antes do pull.

```powershell
php -S 127.0.0.1:8080 -t application router.php
```

Acesse `http://127.0.0.1:8080`. O servidor embutido é para desenvolvimento; o exemplo do Apache está em `deploy/`. O DocumentRoot de produção deve apontar para `application/`.

As páginas CETIDAM e Contato apresentam somente uma mensagem de desenvolvimento. As pendências de implantação e dos dados legados estão documentadas; esta revisão não certifica que o servidor de produção esteja seguro.
