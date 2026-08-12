<?php

namespace DAOs\noticia;

use model\noticia\Noticia;
use DAOS\DAO;
use \PDO;

class NoticiaDAO extends DAO
{

    public function insert(Noticia $model): Noticia|bool
    {
        $query = "INSERT INTO noticia (titulo, subtitulo, descricao, data_pub, status, id_administrador)
        VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->titulo);
        $stmt->bindValue(2, $model->subtitulo);
        $stmt->bindValue(3, $model->descricao);
        $stmt->bindValue(4, $model->data_pub);
        $stmt->bindValue(5, $model->status);
        $stmt->bindValue(6, $model->id_administrador);

        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Noticia $model): Noticia|bool
    {
        $query = "UPDATE noticia SET
                  titulo = ?,
                  subtitulo = ?,
                  descricao = ?,
                  imagem = ?,
                  data_pub = ?,
                  status = ?,
                  id_administrador = ?
                  WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->titulo);
        $stmt->bindValue(2, $model->subtitulo);
        $stmt->bindValue(3, $model->descricao);
        $stmt->bindValue(4, $model->imagem);
        $stmt->bindValue(5, $model->data_pub);
        $stmt->bindValue(6, $model->status);
        $stmt->bindValue(7, $model->id_administrador);
        $stmt->bindValue(7, $model->id);
        return ($stmt->execute()) ? $this->get($model->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM noticia WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }
    public function get(int $id): Noticia|bool
    {
        $query = "SELECT * FROM noticia WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Noticia::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM noticia ORDER BY data_pub DESC;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Noticia::class);
    }

    public function setImg(Noticia $model): bool
    {
        $query = "UPDATE noticia SET 
        imagem = ? 
        WHERE id  = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->imagem);
        $stmt->bindValue(2, $model->id);

        return $stmt->execute();

    }
}
