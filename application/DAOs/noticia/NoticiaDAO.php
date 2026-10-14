<?php

namespace DAOs\noticia;

use model\noticia\Noticia;
use DAOS\DAO;
use \PDO;
use Throwable;

class NoticiaDAO extends DAO
{

    public function insert(Noticia $model): Noticia|bool
    {
        try {

            $query = null;
            $id_usuario = null;

            if ($model->id_administrador !== null) {

                $id_usuario = $model->id_administrador;
                $query = "INSERT INTO noticia (titulo, subtitulo, descricao, data_pub, status, id_administrador)
        VALUES (?, ?, ?, ?, ?, ?);";
            } elseif ($model->id_aluno_adm !== null) {
                $id_usuario = $model->id_aluno_adm;
                $query = "INSERT INTO noticia (titulo, subtitulo, descricao, data_pub, status, id_aluno_adm)
        VALUES (?, ?, ?, ?, ?, ?);";
            }

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $model->titulo);
            $stmt->bindValue(2, $model->subtitulo);
            $stmt->bindValue(3, $model->descricao);
            $stmt->bindValue(4, $model->data_pub);
            $stmt->bindValue(5, $model->status);
            $stmt->bindValue(6, $id_usuario);

            return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
        } catch (Throwable $e) {
            return false;
        }
    }
    public function update(Noticia $model): Noticia|bool
    {
        try {


            $query = "UPDATE noticia SET
                  titulo = ?,
                  subtitulo = ?,
                  descricao = ?,
                  status = ?
                  WHERE id = ?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $model->titulo);
            $stmt->bindValue(2, $model->subtitulo);
            $stmt->bindValue(3, $model->descricao);
            $stmt->bindValue(4, (int)$model->status, PDO::PARAM_INT);
            $stmt->bindValue(5, $model->id);
            return ($stmt->execute()) ? $this->get($model->id) : false;
        } catch (Throwable $e) {
            return false;
        }
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
        try{

        
        $query = "SELECT * FROM noticia WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
         $model = $stmt->fetchObject(Noticia::class);

        return $model;

        }catch (Throwable $e){
            return false;
        }

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
        try {
            $query = "UPDATE noticia SET 
        imagem = ? 
        WHERE id  = ?";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $model->imagem);
            $stmt->bindValue(2, $model->id);

            return $stmt->execute();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function noticia_autor_all(): array|bool
    {
        try {


            $this->pdo->beginTransaction();
            $query1 = "SELECT noticia.*, administrador.nome as nome_autor
                  FROM noticia
                  INNER JOIN administrador ON noticia.id_administrador = administrador.id;";

            $query2 = "SELECT noticia.*, aluno.nome as nome_autor
                  FROM noticia
                  INNER JOIN aluno ON noticia.id_aluno_adm = aluno.id;";

            $consulta = $this->pdo->prepare($query1);
            $consulta->execute();

            $consulta1 = $consulta->fetchAll(PDO::FETCH_CLASS, Noticia::class);

            $consulta = $this->pdo->prepare($query2);
            $consulta->execute();
            $consulta2 = $consulta->fetchAll(PDO::FETCH_CLASS, Noticia::class);
            $this->pdo->commit();

            return array_merge($consulta1, $consulta2);
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
