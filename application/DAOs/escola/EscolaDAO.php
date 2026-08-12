<?php

namespace DAOs\escola;

use model\escola\Escola;
use \PDO;
use DAOS\DAO;

class EscolaDAO extends DAO
{

    public function insert(Escola $model): Escola|bool
    {
        $query = "INSERT INTO escola (nome, cod_inep, ano_letivo, logo_img, id_contato, id_endereco)
        VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->cod_inep);
        $stmt->bindValue(3, $model->ano_letivo);
        $stmt->bindValue(4, $model->logo_img); 
        $stmt->bindValue(5, $model->id_contato);
        $stmt->bindValue(6, $model->id_endereco);
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Escola $model): Escola|bool
    {
        $query = "UPDATE escola SET
                  nome = ?,
                  cod_inep = ?,
                  ano_letivo = ?,
                  logo_img = ?,
                  id_contato = ?,
                  id_endereco = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->cod_inep);
        $stmt->bindValue(3, $model->ano_letivo);
        $stmt->bindValue(4, $model->logo_img);
        $stmt->bindValue(5, $model->id_contato);
        $stmt->bindValue(6, $model->id_endereco);
        $stmt->bindValue(7, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM escola WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }
    public function get(int $id): Escola|bool
    {
        $query = "SELECT * FROM escola WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Escola::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM escola;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Escola::class);
    }
}
