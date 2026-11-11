# Remoção do recurso Escola

Alteração solicitada pelo responsável em 11/11/2026: a tabela `escola` não será utilizada no portal.

## Alterações

- Removida a tabela `escola` do banco ativo, incluindo seus 20 registros.
- Criado backup privado completo antes da exclusão, fora do projeto, em `%LOCALAPPDATA%/PortalCETIDAM/backups/`. O caminho exato foi registrado em `tests/.artifacts/remocao_escola_migration.txt`.
- Adicionada a migração `002_remover_escola.sql`, executada pelo fluxo existente de migração após o backup. O DROP não desativa a verificação de chaves estrangeiras.
- Removidos `application/model/escola/Escola.php`, `application/DAOs/escola/EscolaDAO.php` e `application/controller/escola/EscolaController.php`, sem chamadas ativas nas rotas ou views.
- Atualizado `database/esquema_atual.sql` para as 8 tabelas restantes. `esquema_inicial.sql` continua como registro histórico, sem dados.
- Retirados os testes de CRUD do módulo excluído e ajustada a verificação de restauração para as 8 tabelas atuais, com verificações explícitas da ausência de `escola`.

## Verificação

A inspeção confirmou a ausência da tabela e comparou contagens e resumos SHA-256 dos registros de todas as outras tabelas antes/depois: os dados permaneceram idênticos. O resumo está em `tests/.artifacts/remocao_escola.json`.

Regressão após a exclusão: 1.576 verificações principais e 66 complementares aprovadas, sem falhas; 77 arquivos PHP sem erro de sintaxe. Os detalhes estão em [Testes e pendências](05_TESTES_E_PENDENCIAS.md). As páginas e os textos institucionais existentes não dependiam dessa tabela e não precisaram ser alterados.
