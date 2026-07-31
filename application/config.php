<?php

$_ENV["db"]["host"] = "localhost:3306";
$_ENV["db"]["user"] = "root";
$_ENV["db"]["pass"] = "120777";
$_ENV["db"]["dbname"] = "portal_aristides_maltez";
$_ENV["db"]["dbserver"] = "mysql";
$_ENV["db"]["charset"] = "utf8mb4";
$_ENV["db"]["dsn"] = $_ENV['db']['dbserver'] .
    ':host=' . $_ENV['db']['host'] .
    ';dbname=' . $_ENV['db']['dbname'] .
    ';charset=' . $_ENV['db']['charset'];

define('BASE_DIR', dirname(__FILE__, 1) . "/");

define('VIEW', BASE_DIR . "view/");
