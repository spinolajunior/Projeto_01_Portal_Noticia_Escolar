<?php

namespace DAOs\usuario;

use DAOs\DAO;
use DAOs\administrador\AdministradorDAO;
use DAOs\aluno\AlunoDAO;
use DAOs\credenciais\CredenciaisDAO;
use model\credenciais\Credenciais;
use Throwable;
use PDO;


class UsuarioDAO extends DAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // esta função tem como objetivo fazer o update dos dados dos usuarios.
    public function atualizarAdm(array $array): bool
    {
        try {
            $usuario = $array['usuario'];
            $credencial = $array['credencial'];
            $foto = $array['foto_update'];
            $this->pdo->beginTransaction();

            $query = "UPDATE administrador SET
                  nome = ?,
                  matricula = ?
                  WHERE id = ?;";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $usuario->nome);
            $stmt->bindValue(2, $usuario->matricula);
            $stmt->bindValue(3, $usuario->id);
            $stmt->execute();

            if ($credencial->senha != ''  && $credencial->usuario != '') {

                $query = "UPDATE credenciais SET
                  usuario = ?,
                  senha = SHA2(?,256)
                  WHERE id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $credencial->usuario);
                $stmt->bindValue(2, $credencial->senha);
                $stmt->bindValue(3, $credencial->id);
                $stmt->execute();


                // amboms devem ser atualizados!
            } elseif ($credencial->senha != '' && $credencial->usuario == '') {
                // aqui somente a senha deve ser alterada!
                $query = "UPDATE credenciais SET
                  senha = SHA2(?,256)
                  WHERE id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $credencial->senha);
                $stmt->bindValue(2, $credencial->id);
                $stmt->execute();
            } elseif ($credencial->senha == '' && $credencial->usuario != '') {

                $query = "UPDATE credenciais SET
                  usuario = ?
                  WHERE id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $credencial->usuario);
                $stmt->bindValue(2, $credencial->id);
                $stmt->execute();
            }

            if ($foto !== null) {

                $query = "UPDATE credenciais SET  foto = ? where id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $foto, PDO::PARAM_STR);
                $stmt->bindValue(2, $credencial->id, PDO::  PARAM_INT);
                $stmt->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    public function atualizarAluno(array $array): bool
    {
        try {

            $usuario = $array['usuario'];
            $credencial = $array['credencial'];
            $foto = $array['foto_update'];
            $this->pdo->beginTransaction();

            $query = "UPDATE aluno SET
                  nome = ?,
                  matricula = ?,
                  data_nascimento = ?,
                  id_serie = ?
                  WHERE id = ?;";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $usuario->nome);
            $stmt->bindValue(2, $usuario->matricula);
            $stmt->bindValue(3, $usuario->data_nascimento);
            $stmt->bindValue(4, $usuario->id_serie);
            $stmt->bindValue(5, $usuario->id);
            $stmt->execute();

            if ($credencial->senha != ''  && $credencial->usuario != '') {

                $query = "UPDATE credenciais SET
                  usuario = ?,
                  senha = SHA2(?,256)
                  WHERE id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $credencial->usuario);
                $stmt->bindValue(2, $credencial->senha);
                $stmt->bindValue(3, $credencial->id);

            
                $stmt->execute();


                // amboms devem ser atualizados!
            } elseif ($credencial->senha != '' && $credencial->usuario == '') {
                // aqui somente a senha deve ser alterada!
                $query = "UPDATE credenciais SET
                  senha = SHA2(?,256)
                  WHERE id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $credencial->senha);
                $stmt->bindValue(2, $credencial->id);
                $stmt->execute();
            } elseif ($credencial->senha == '' && $credencial->usuario != '') {

                $query = "UPDATE credenciais SET
                  usuario = ?
                  WHERE id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $credencial->usuario);
                $stmt->bindValue(2, $credencial->id);
                $stmt->execute();
            }

            if ($foto !== null) {

                $query = "UPDATE credenciais SET  foto = ? where id = ?;";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $foto, PDO::PARAM_STR);
                $stmt->bindValue(2, $credencial->id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }
}
