<?php

namespace DAOs\endereco;

use model\endereco\Endereco;
use \DAOS\DAO;
use \PDO;

class EnderecoDAO extends DAO
{

   public function insert(Endereco $model): Endereco|bool
    {
        $query = "INSERT INTO endereco (cidade, cep, bairro, rua, complemento)
        VALUES (?, ?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->cidade);
        $stmt->bindValue(2, $model->cep);
        $stmt->bindValue(3, $model->bairro);
        $stmt->bindValue(4, $model->rua);
        $stmt->bindValue(5, $model->complemento);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Endereco $model): Endereco|bool
    {
        $query = "UPDATE endereco SET
                  cidade = ?,
                  cep = ?,
                  bairro = ?,
                  rua = ?,
                  complemento = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->cidade);
        $stmt->bindValue(2, $model->cep);
        $stmt->bindValue(3, $model->bairro);
        $stmt->bindValue(4, $model->rua);
        $stmt->bindValue(5, $model->complemento);
        $stmt->bindValue(6, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM endereco WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }
    public function get(int $id): Endereco|bool
    {
        $query = "SELECT * FROM ednereco WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Endereco::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM endereco;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Endereco::class);
    }
}
