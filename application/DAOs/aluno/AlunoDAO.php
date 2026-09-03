<?php

namespace DAOs\aluno;

use model\aluno\Aluno;
use DAOS\DAO;
use \PDO;

class AlunoDAO extends DAO
{

    public function insert(Aluno $model): Aluno|bool
    {
        $query = "INSERT INTO aluno (nome, matricula, data_nascimento, serie, id_credenciais)
        VALUES (?, ?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->matricula);
        $stmt->bindValue(3, $model->data_nascimento);
        $stmt->bindValue(4, $model->serie);
        $stmt->bindValue(5, $model->id_credenciais);
        return ($stmt->execute()) ? $this->getById((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Aluno $model): Aluno|bool
    {
        $query = "UPDATE aluno SET
                  nome = ?,
                  matricula = ?,
                  data_nascimento = ?,
                  serie = ?,
                  id_credenciais = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->matricula);
        $stmt->bindValue(3, $model->data_nascimento);
        $stmt->bindValue(4, $model->serie);
        $stmt->bindValue(5, $model->id_credenciais);
        $stmt->bindValue(6, $model->id);
        return ($stmt->execute()) ? $this->getById($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM aluno WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }
    public function getById(int $id): Aluno|bool
    {
        $query = "SELECT * FROM aluno WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Aluno::class);
        return ($model !== false) ? $model : false;
    }
    public function get(): array
    {
        $query = "SELECT * FROM aluno;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Aluno::class);
    }

    public function idCredConf(int $id): bool|Aluno{
       $query = "SELECT * FROM aluno where id_credenciais = ?;";
       $stmt = $this->pdo->prepare($query);
       $stmt->bindValue(1, $id);
       $stmt->execute();
       $aluno = $stmt->fetchObject(Aluno::class);
       return $aluno !== false ? $aluno : false;

    }
}
