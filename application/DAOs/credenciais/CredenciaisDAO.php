<?php

namespace DAOs\credenciais;

use DAOs\DAO;
use DateTime;
use model\credenciais\Credenciais;
use model\credenciais\CredenciaisCadastroAluno;
use model\credenciais\CredenciaisCadastroAdministrador;

use \PDO;
use Throwable;

class CredenciaisDAO extends DAO
{

    public function insert(Credenciais $model): Credenciais|bool
    {
        $query = "INSERT INTO credenciais (usuario, senha)
        VALUES (?, SHA2(?,256));";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->usuario);
        $stmt->bindValue(2, $model->senha);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Credenciais $model): Credenciais|bool
    {
        try {
            $consulta = $model->get();
            if ($consulta->usuario !== $model->usuario or $consulta->senha !== $model->senha) {
                $query = "UPDATE credenciais SET
                  usuario = ?,
                  senha = SHA2(?,256)
                  WHERE id = ?;";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(1, $model->usuario);
                $stmt->bindValue(2, $model->senha);
                $stmt->bindValue(3, $model->id);
                return ($stmt->execute()) ? $this->get($model->id) : false;
            }
        } catch (Throwable $e) {
            return false;
        }
        return false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM credenciais WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }
    public function get(int $id): Credenciais|bool
    {
        $query = "SELECT * FROM credenciais WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Credenciais::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM credenciais;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Credenciais::class);
    }
    public function logar(Credenciais $credencial): bool|Credenciais
    {
        try {
            $query = "SELECT * FROM credenciais WHERE usuario = ? AND Senha = sha2(?,256)  ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $credencial->usuario);
            $stmt->bindValue(2, $credencial->senha);
            $stmt->execute();
            $obj = $stmt->fetchObject(Credenciais::class);
            if ($obj !== false) {
                $this->updateLastLogin($obj->id);
                $stmt->execute();
                $obj = $stmt->fetchObject(Credenciais::class);
                return $obj;
            } else {
                return false;
            }
        } catch (Throwable $e) {
            return false;
        }
    }

    public function adicionarFoto(Credenciais $obj): bool
    {
        try {
            $query = "UPDATE credenciais SET  foto = ? where id = ?;";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->foto, PDO::PARAM_STR);
            $stmt->bindValue(2, $obj->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Throwable $e) {
            return false;
        }
    }

    function updateLastLogin(int $id): bool
    {
        $time = new DateTime();
        $query = "UPDATE credenciais SET 
        last_login = ? where id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $time->format("Y-m-d H:i:s"));
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }
    function cadastroAluno(CredenciaisCadastroAluno $obj): bool
    {
        try {
            $this->pdo->beginTransaction();
            $query = "INSERT INTO credenciais (usuario,senha) VALUES (?,sha2(?,256));";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->usuario);
            $stmt->bindValue(2, $obj->senha);
            $stmt->execute();

            $idCredBuffer = $this->pdo->lastInsertId();

            $query = "INSERT INTO aluno (nome,matricula,data_nascimento,id_credenciais,id_serie) 
           VALUES (?,?,?,?,?);";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->nome);
            $stmt->bindValue(2, $obj->matricula);
            $stmt->bindValue(3, $obj->data_nascimento);
            $stmt->bindValue(4, $idCredBuffer, PDO::PARAM_INT);
            $stmt->bindValue(5, $obj->id_serie, PDO::PARAM_INT);


            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    function cadastroAdm(CredenciaisCadastroAdministrador $obj): bool
    {
        try {
            $this->pdo->beginTransaction();
            $query = "INSERT INTO credenciais (usuario,senha) VALUES (?,sha2(?,256));";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->usuario);
            $stmt->bindValue(2, $obj->senha);
            $stmt->execute();

            $idCredBuffer = $this->pdo->lastInsertId();

            $query = "INSERT INTO administrador (nome,matricula,id_credenciais,id_tipo_adm)
           VALUES (?,?,?,?);";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->nome);
            $stmt->bindValue(2, $obj->matricula);
            $stmt->bindValue(3, $idCredBuffer, PDO::PARAM_INT);
            $stmt->bindValue(4, $obj->id_tipo_adm, PDO::PARAM_INT);


            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            echo ($e->getMessage());
            return false;
        }
    }

    function acesso(Credenciais $obj): bool
    {
        $id = $obj->id;
        $ativo = $obj->ativo;

        try {
            $this->pdo->beginTransaction();
            $query = "UPDATE credenciais set ativo = ? where id = ?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $ativo, PDO::PARAM_BOOL);
            $stmt->bindValue(2, $id);
            $stmt->execute();
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    function buscarFotoAutorPubli(int $id): string|bool
    {
        try {
            $query = "SELECT foto from credenciais where id = ?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Throwable $e) {
            return false;
        }
    }
}
