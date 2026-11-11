<?php

namespace DAOs\noticia;

use model\noticia\Noticia;
use DAOs\DAO;
use PDO;

class NoticiaDAO extends DAO
{
    public function insert(Noticia $model): Noticia|bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO noticia (titulo, subtitulo, descricao, imagem, data_pub, status, id_administrador, id_aluno_adm) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$model->titulo, $model->subtitulo, $model->descricao, $model->imagem, $model->data_pub, (int)$model->status, $model->id_administrador, $model->id_aluno_adm]) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }

    public function update(Noticia $model): Noticia|bool
    {
        $stmt = $this->pdo->prepare('UPDATE noticia SET titulo = ?, subtitulo = ?, descricao = ?, imagem = ?, status = ? WHERE id = ?');
        return $stmt->execute([$model->titulo, $model->subtitulo, $model->descricao, $model->imagem, (int)$model->status, $model->id]) ? $this->get($model->id) : false;
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM noticia WHERE id = ?')->execute([$id]);
    }

    public function get(int $id): Noticia|bool
    {
        $stmt = $this->pdo->prepare('SELECT * FROM noticia WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchObject(Noticia::class);
    }

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM noticia ORDER BY data_pub DESC, id DESC')->fetchAll(PDO::FETCH_CLASS, Noticia::class);
    }

    public function setImg(Noticia $model): bool
    {
        return $this->pdo->prepare('UPDATE noticia SET imagem = ? WHERE id = ?')->execute([$model->imagem, $model->id]);
    }

    public function noticia_autor_all(): array|bool
    {
        $query = 'SELECT noticia.*, COALESCE(administrador.nome, aluno.nome) AS nome_autor FROM noticia LEFT JOIN administrador ON administrador.id = noticia.id_administrador LEFT JOIN aluno ON aluno.id = noticia.id_aluno_adm ORDER BY noticia.data_pub DESC, noticia.id DESC';
        return $this->pdo->query($query)->fetchAll(PDO::FETCH_CLASS, Noticia::class);
    }
}
