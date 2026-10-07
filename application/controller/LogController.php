<?php

namespace controller;

use view\View;

class LogController 
{
    public function __construct( )
    {
        // esta classe tera em seu construtor toda a logica para cadastrar um log no sistema;
    }

    public static function log(): array
    {
        $log = [];
        $file =  BASE_DIR . 'logs/log_2026_10.log';

        if (file_exists($file)) {
            $file = file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            foreach ($file as $item) {

                $elemento = json_decode($item);
                if ($elemento !== null) {
                    $log[] = $elemento;
                }
            }
        }
        return $log;

    }

    public static function painelLog(){
        LoginController::logadoRedirect("/painel/logs");
        $model = ["logs" => self::log()];
        new View("LOGs do sistema!",View::$nav_footer,View::$dinamicoPaineis.'log_painel.php',$model)->renderizar();
    }
}
