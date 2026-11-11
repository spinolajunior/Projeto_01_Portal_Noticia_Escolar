<?php
// Router apenas para desenvolvimento: php -S 127.0.0.1:8080 -t application router.php
$caminho = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$raiz = realpath(__DIR__ . '/application');
$arquivo = realpath($raiz . $caminho);
if (preg_match('~^/(?:assets/|view/img/)[a-zA-Z0-9_./-]+\.(?:css|js|jpg|jpeg|png|webp|woff2?)$~D', $caminho)
    && !str_contains($caminho, '..') && $arquivo !== false && str_starts_with($arquivo, $raiz . DIRECTORY_SEPARATOR) && is_file($arquivo)) {
    return false;
}
require __DIR__ . '/application/index.php';
