<?php

namespace DAOs;

use \PDO;

abstract class DAO
{
    public ?PDO $pdo = null;
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
        if ($this->pdo === null) {
            $this->pdo = new PDO(
                $_ENV["db"]["dsn"],
                $_ENV["db"]["user"],
                $_ENV["db"]["pass"],
                [
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        }
    }

   
}
