<?php

namespace DAOs\administrador;

use model\administrador\Administrador;
use DAOs\DAO;
use \PDO;

class AdministradorDAO extends DAO
{

    public function insert(Administrador $model): Administrador|bool
    {
        $query = "INSERT INTO administrador (nome, matricula, cpf, id_credenciais, id_tipo_adm, id_contato, id_endereco)
        VALUES (?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->matricula);
        $stmt->bindValue(3, $model->cpf);
        $stmt->bindValue(4, $model->id_credenciais);
        $stmt->bindValue(5, $model->id_tipo_adm);
        $stmt->bindValue(6, $model->id_contato);
        $stmt->bindValue(7, $model->id_endereco);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Administrador $model): Administrador|bool
    {
        $query = "UPDATE administrador SET
                  nome = ?,
                  matricula = ?,
                  cpf = ?,
                  id_credenciais = ?,
                  id_tipo_adm = ?,
                  id_contato = ?,
                  id_endereco = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->matricula);
        $stmt->bindValue(3, $model->cpf);
        $stmt->bindValue(4, $model->id_credenciais);
        $stmt->bindValue(5, $model->id_tipo_adm);
        $stmt->bindValue(6, $model->id_contato);
        $stmt->bindValue(7, $model->id_endereco);
        $stmt->bindValue(8, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM administrador WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }
    public function get(int $id): Administrador|bool
    {
        $query = "SELECT * FROM administrador WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Administrador::class);
        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM administrador;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Administrador::class);
    }
}
