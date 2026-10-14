<?php

namespace DAOs\evento;

use DAOs\DAO;
use model\evento\Evento;
use PDO;
use Throwable;

class EventoDAO extends DAO
{
    public function insert(Evento $evento): Evento|bool
    {
        try {

            $query = null;
            $id_usuario = null;

            if ($evento->id_administrador != null) {
                $id_usuario = $evento->id_administrador;
                $query = "INSERT INTO evento (titulo,data_evento,id_administrador)
                  VALUES (?,?,?);";
            } elseif ($evento->id_aluno_adm != null) {
                $id_usuario = $evento->id_aluno_adm;
                $query = "INSERT INTO evento (titulo,data_evento,id_aluno_adm)
                  VALUES (?,?,?);";
            }


            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $evento->titulo);
            $stmt->bindValue(2, $evento->data_evento);
            $stmt->bindValue(3, $id_usuario);

            return ($stmt->execute()) ? $this->getById((int)$this->pdo->lastInsertId()) : false;
        } catch (Throwable $e) {
            return false;
        }
    }
    public function get(): array|bool
    {
        $query = ("SELECT * FROM evento ;");
        $stmt = $this->pdo->prepare($query);
        return ($stmt->execute()) ? $stmt->fetchAll(PDO::FETCH_CLASS, Evento::class) : false;
    }
    public function getById(int $id): Evento|false
    {
        $query = ("SELECT * FROM evento WHERE id=?;");
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        return ($stmt->execute()) ? $stmt->fetchObject(Evento::class) : false;
    }
    public function update(Evento $evento): Evento|bool
    {
        $query = "UPDATE evento SET 
        titulo = ?,
        data_evento = ?
         WHERE id = ?;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $evento->titulo);
        $stmt->bindValue(2, $evento->data_evento);
        $stmt->bindValue(3, $evento->id);
        return ($stmt->execute()) ? $this->getById($evento->id) : false;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM evento WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
