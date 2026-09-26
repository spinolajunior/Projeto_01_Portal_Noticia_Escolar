<?php

namespace DAOs\aviso;

use model\aviso\Aviso;
use \PDO;
use DAOs\DAO;

class AvisoDAO extends DAO
{

    public function insert(Aviso $model): Aviso|bool
    {
        $query = "INSERT INTO aviso (titulo, ativo, id_administrador)
                  VALUES (?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->titulo);
        $stmt->bindValue(2, $model->ativo);
        $stmt->bindValue(3, $model->id_administrador);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Aviso $model): Aviso|bool
    {
        $query = "UPDATE aviso SET
                  titulo = ?,
                  ativo = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->titulo);
        $stmt->bindValue(2, (int)$model->ativo,PDO::PARAM_INT);
        $stmt->bindValue(3, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM aviso WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }
    public function get(int $id): Aviso|bool
    {
        $query = "SELECT * FROM aviso WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Aviso::class);
        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM aviso;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Aviso::class);
    }
}
