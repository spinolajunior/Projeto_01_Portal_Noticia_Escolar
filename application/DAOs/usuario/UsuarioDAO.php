<?php

namespace DAOs\usuario;

use DAOs\DAO;
use DAOs\credenciais\CredenciaisDAO;
use Throwable;

class UsuarioDAO extends DAO
{
    public function atualizarAdm(array $array): bool
    {
        return $this->atualizarDados($array, 'administrador');
    }

    public function atualizarAluno(array $array): bool
    {
        return $this->atualizarDados($array, 'aluno');
    }

    private function atualizarDados(array $array, string $tipo): bool
    {
        try {
            $usuario = $array['usuario'];
            $credencial = $array['credencial'];
            $this->pdo->beginTransaction();
            new CredenciaisDAO($this->pdo)->validarMatricula($usuario->matricula, $credencial->id);
            // O vínculo vem do banco, não de um ID de credencial enviado pelo formulário.
            $stmt = $this->pdo->prepare("SELECT id_credenciais FROM $tipo WHERE id = ? FOR UPDATE");
            $stmt->execute([$usuario->id]);
            if ((int)$stmt->fetchColumn() !== $credencial->id) {
                throw new \RuntimeException('Vínculo de credencial inválido.');
            }
            if ($tipo === 'aluno') {
                $stmt = $this->pdo->prepare('UPDATE aluno SET nome = ?, matricula = ?, data_nascimento = ?, id_serie = ? WHERE id = ?');
                $stmt->execute([$usuario->nome, $usuario->matricula, $usuario->data_nascimento, $usuario->id_serie, $usuario->id]);
            } else {
                $stmt = $this->pdo->prepare('UPDATE administrador SET nome = ?, matricula = ? WHERE id = ?');
                $stmt->execute([$usuario->nome, $usuario->matricula, $usuario->id]);
            }
            $dao = new CredenciaisDAO($this->pdo);
            if (!$dao->update($credencial)) {
                throw new \RuntimeException('Falha ao atualizar credencial.');
            }
            if ($array['foto_update'] !== null) {
                $credencial->foto = $array['foto_update'];
                if (!$dao->adicionarFoto($credencial)) {
                    throw new \RuntimeException('Falha ao atualizar foto.');
                }
            }
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
