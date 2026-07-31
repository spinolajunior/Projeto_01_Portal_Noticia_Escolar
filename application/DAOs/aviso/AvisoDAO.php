<?php

namespace DAOs\aviso;

use model\aviso\Aviso;
use \PDO;
use DAOs\DAO;

class AvisoDAO extends DAO
{

    public function insert(Aviso $model): Aviso|bool
    {
        $query = "INSERT INTO aviso (titulo, descricao, data_aviso,
                  validade, prioridade, status, id_administrador)
                  VALUES (?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->titulo);
        $stmt->bindValue(2, $model->descricao);
        $stmt->bindValue(3, $model->data_aviso);
        $stmt->bindValue(4, $model->validade);
        $stmt->bindValue(5, $model->prioridade);
        $stmt->bindValue(6, $model->status);
        $stmt->bindValue(7, $model->id_administrador);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Aviso $model): Aviso|bool
    {
        $query = "UPDATE aviso SET
                  titulo = ?,
                  descricao = ?,
                  data_aviso = ?,
                  validade = ?,
                  prioridade = ?,
                  status = ?,
                  id_administrador = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->titulo);
        $stmt->bindValue(2, $model->descricao);
        $stmt->bindValue(3, $model->data_aviso);
        $stmt->bindValue(4, $model->validade);
        $stmt->bindValue(5, $model->prioridade);
        $stmt->bindValue(6, $model->status);
        $stmt->bindValue(7, $model->id_administrador);
        $stmt->bindValue(8, $model->id);
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
