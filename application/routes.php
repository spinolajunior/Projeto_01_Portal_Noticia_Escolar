<?php
use controller\credenciais\CredenciaisController;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($uri) {
    case "/":
        include VIEW . "home/Home.php";
        break;
    case "/credenciais":
        CredenciaisController::getAll();
        break;
    default:
        break;
}
