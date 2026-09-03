<?php

namespace view;

class View
{
    public static string $logado = VIEW . "template/Nav_footer_out.php";
    public static string $out = VIEW . "template/Nav_footer_logado.php";
    public static string $limpo = VIEW . "template/Limpo.php";
    public function __construct(
        public string $titulo,
        public ?string $base,
        public ?string $main,
        public ?array $model,
    ) {}



    public function renderizar(): void
    {
        //extract($this->model);
        $titulo = $this->titulo;

        ob_start();

        $model = $this->model;

        if (file_exists($this->main)) {
            include $this->main;
        } else {
            echo "<p>Erro: View do conteúdo não encontrada ({$this->main})</p>";
        }

        $content = ob_get_clean();

        if (file_exists($this->base)) {

            include $this->base;
        } else {
            echo "<p>Erro: View base não encontrada ({$this->base})</p>";
        }
    }
}
