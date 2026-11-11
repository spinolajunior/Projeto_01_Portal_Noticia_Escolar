-- Referência das alterações. O script scripts/migrar_banco.php verifica
-- o estado atual e faz backup antes de aplicar cada mudança necessária.
ALTER TABLE credenciais DROP INDEX senha;
ALTER TABLE credenciais MODIFY senha VARCHAR(255) NOT NULL;
ALTER TABLE aviso MODIFY titulo VARCHAR(80) NOT NULL;
-- Índices de matrícula só são aplicados quando não há duplicidades legadas.
-- CREATE UNIQUE INDEX uq_aluno_matricula ON aluno (matricula);
-- CREATE UNIQUE INDEX uq_administrador_matricula ON administrador (matricula);
