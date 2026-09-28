<?php

namespace DAOs\serie;

use DAOs\DAO;
use model\serie\Serie;
use PDO;


class SerieDAO extends DAO
{


    public  function getById(int $id): Serie|bool
    {
        $query = "SELECT * from serie where id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, (int)$id, PDO::PARAM_INT);

        return ($stmt->execute()) ? $stmt->fetchObject(Serie::class) : false;
    }
    public  function get():array|bool {
        $query = "SELECT * from serie;";
        $stmt=$this->pdo->prepare($query);
        $stmt->execute();
        $stmt= $stmt->fetchAll(PDO::FETCH_CLASS,Serie::class);
        return (count($stmt) > 0)?$stmt:false;
    }
    public  function update() {}
    public  function insert() {}
    public  function delete() {}
}
