<?php

namespace DAOs\contato;

use model\contato\Contato;
use DAOs\DAO;
use \PDO;


class ContatoDAO extends DAO
{

    public function insert(Contato $model): Contato|bool
    {
        $query = "INSERT INTO contato (tel, email)
                  VALUES (?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->tel);
        $stmt->bindValue(2, $model->email);
        
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Contato $model): Contato|bool
    {
        $query = "UPDATE contato SET
                  tel = ?,
                  email = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->tel);
        $stmt->bindValue(2, $model->email);
        $stmt->bindValue(3, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM contato WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }
    public function get(int $id): Contato|bool
    {
        $query = "SELECT * FROM contato WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Contato::class);
        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM contato;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Contato::class);
    }
}
