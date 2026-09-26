<?php

namespace DAOs\evento;

use DAOs\DAO;
use model\evento\Evento;
use PDO;

class EventoDAO extends DAO
{
    public function insert(Evento $evento): Evento|bool
    {
        $query = "INSERT INTO evento (titulo,data_evento,id_administrador)
                  VALUES (?,?,?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $evento->titulo);
        $stmt->bindValue(2, $evento->data_evento);
        $stmt->bindValue(3, $evento->id_administrador);
        return ($stmt->execute()) ? $this->getById((int)$this->pdo->lastInsertId()) : false;
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
