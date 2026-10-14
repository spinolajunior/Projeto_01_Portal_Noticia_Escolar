<?php

namespace DAOs\tipo_adm;

use model\tipo_adm\Tipo_adm;
use DAOs\DAO;
use \PDO;

class TipoAdmDAO extends DAO
{

    public function insert(Tipo_adm $model): Tipo_adm|bool
    {
        $query = "INSERT INTO tipo_adm (cargo, nivel_acesso)
        VALUES (?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->cargo);
        $stmt->bindValue(2, $model->nivel_acesso);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Tipo_adm $model): Tipo_adm|bool
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
    public function get(int $id): Tipo_adm|bool
    {
        $query = "SELECT * FROM tipo_adm WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Tipo_adm::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM tipo_adm;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Tipo_adm::class);
    }
}
