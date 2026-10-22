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
        try {


            $query = "SELECT * FROM noticia WHERE id = ?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $model = $stmt->fetchObject(Noticia::class);

            return $model;
        } catch (Throwable $e) {
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
        if (isset($_SESSION['acesso']) && isset($_SESSION['id_usuario'])) {

            $acesso_usuario = (int)$_SESSION['acesso'];
            $id_usuario = (int)$_SESSION['id_usuario'];

            try {

                $query1 = "SELECT noticia.*, administrador.nome AS nome_autor
           FROM noticia
           INNER JOIN administrador ON administrador.id = noticia.id_administrador
           INNER JOIN tipo_adm ON tipo_adm.id = administrador.id_tipo_adm
           WHERE tipo_adm.nivel_acesso < ?
              OR administrador.id = ?
           ORDER BY noticia.data_pub DESC;";

                $query2 = "SELECT noticia.*, aluno.nome AS nome_autor
                       FROM noticia
                       INNER JOIN aluno ON aluno.id = noticia.id_aluno_adm
                       ORDER BY noticia.data_pub DESC;";

                $consulta = $this->pdo->prepare($query1);
                $consulta->bindValue(1, $acesso_usuario, PDO::PARAM_INT);
                $consulta->bindValue(2,$id_usuario,PDO::PARAM_INT);
                if ($consulta->execute()) {
                    $consulta1 = $consulta->fetchAll(PDO::FETCH_CLASS, Noticia::class);
                    $consulta = $this->pdo->prepare($query2);
                    if ($consulta->execute()) {
                        $consulta2 = $consulta->fetchAll(PDO::FETCH_CLASS, Noticia::class);
                        return array_merge($consulta1, $consulta2);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } catch (Throwable $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}
