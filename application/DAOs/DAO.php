<?php

namespace DAOs;

use \PDO;
use PDOException;

abstract class DAO
{
    public ?PDO $pdo = null;
    public function __construct()
    {
        try {
            if ($this->pdo === null) {
                $this->pdo = new PDO(
                    $_ENV["db"]["dsn"],
                    $_ENV["db"]["user"],
                    $_ENV["db"]["pass"],
                    [
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ]
                );
            }
        } catch (PDOException $e) {
            echo "Falha ao conectar ao banco de dados " . $e->getMessage();
        }
    }

   
}
