<?php

namespace DAOs\credenciais;

use DAOs\DAO;
use model\credenciais\Credenciais;
use \PDO;

class CredenciaisDAO extends DAO
{

    public function insert(Credenciais $model): Credenciais|bool
    {
        $query = "INSERT INTO credenciais (usuario, senha, last_login, ativo)
        VALUES (?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->usuario);
        $stmt->bindValue(2, $model->senha);
        $stmt->bindValue(3, $model->last_login);
        $stmt->bindValue(4, $model->ativo);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Credenciais $model): Credenciais|bool
    {
        $query = "UPDATE credenciais SET
                  usuario = ?,
                  senha = ?,
                  last_login = ?,
                  ativo = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->usuario);
        $stmt->bindValue(2, $model->senha);
        $stmt->bindValue(3, $model->last_login);
        $stmt->bindValue(4, $model->ativo);
        $stmt->bindValue(5, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
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
}
