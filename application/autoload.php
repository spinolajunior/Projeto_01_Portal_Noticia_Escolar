<?php

spl_autoload_register(function (string $nome_da_classe): void {
    $arquivo = BASE_DIR . str_replace('\\', '/', $nome_da_classe) . '.php';
    if (is_file($arquivo)) {
        require_once $arquivo;
    }
});
