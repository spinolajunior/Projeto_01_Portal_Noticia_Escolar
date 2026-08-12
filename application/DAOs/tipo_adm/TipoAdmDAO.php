<?php

namespace DAOs\tipo_adm;

use model\tipo_adm\TipoAdm;
use DAOs\DAO;
use \PDO;

class TipoAdmDAO extends DAO
{

    public function insert(TipoAdm $model): TipoAdm|bool
    {
        $query = "INSERT INTO tipo_adm (cargo, nivel_acesso)
        VALUES (?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->cargo);
        $stmt->bindValue(2, $model->nivel_acesso);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(TipoAdm $model): TipoAdm|bool
    {
        $query = "UPDATE tipo_adm SET
                  cargo = ?,
                  nivel_acesso = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->cargo);
        $stmt->bindValue(2, $model->nivel_acesso);
        $stmt->bindValue(3, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM tipo_adm WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }
    public function get(int $id): TipoAdm|bool
    {
        $query = "SELECT * FROM tipo_adm WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(TipoAdm::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM tipo_adm;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, TipoAdm::class);
    }
}
