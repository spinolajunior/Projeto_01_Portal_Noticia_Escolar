<?php

namespace controller;

use view\View;

class LogController
{
    public static function registrar(string $evento, string $status, ?string $entidade = null, ?int $id = null, ?string $mensagem = null): void
    {
        $pasta = PRIVATE_DIR . '/logs';
        if (!is_dir($pasta) && !mkdir($pasta, 0700, true) && !is_dir($pasta)) {
            error_log('Falha ao preparar o log de auditoria.');
            return;
        }
        $registro = [
            'data' => date('Y-m-d H:i:s'), 'evento' => $evento, 'status' => $status,
            'nome_usuario' => $_SESSION['usuario'] ?? 'Visitante',
            'id_credencial' => $_SESSION['id_credencial'] ?? null,
            'entidade' => $entidade, 'registro_id' => $id,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI', 'mensagem' => $mensagem,
        ];
        if (file_put_contents($pasta . '/log_' . date('Y_m') . '.log', json_encode($registro, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) . PHP_EOL, FILE_APPEND | LOCK_EX) === false) {
            error_log('Falha ao gravar o log de auditoria.');
        }
    }

    public static function log(): array
    {
        $arquivo = PRIVATE_DIR . '/logs/log_' . date('Y_m') . '.log';
        if (!is_file($arquivo)) {
            return [];
        }
        // Limita a leitura ao trecho recente para o painel não crescer sem limite.
        $file = fopen($arquivo, 'rb');
        $tamanho = filesize($arquivo);
        if ($tamanho > 1048576) {
            fseek($file, -1048576, SEEK_END);
            fgets($file);
        }
        $registros = [];
        while (($linha = fgets($file)) !== false) {
            $registro = json_decode($linha);
            if (is_object($registro)) {
                $registros[] = $registro;
            }
        }
        fclose($file);
        return array_reverse(array_slice($registros, -1000));
    }

    public static function painelLog(): void
    {
        LoginController::logadoRedirect('/painel/logs');
        Controller::verificarAcesso((object)$_SESSION, 4);
        new View('Logs do sistema', View::$nav_footer, View::$dinamicoPaineis . 'log_painel.php', ['logs' => self::log()])->renderizar();
    }
}
