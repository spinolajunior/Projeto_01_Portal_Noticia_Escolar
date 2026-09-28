<?php

namespace view;

class View
{
    public static string $nav_footer = VIEW . "template/nav_footer.php";
    public static string $limpo = VIEW . "template/limpo.php";
    public static string $dinamico = VIEW . "include/pages/dinamico/";
    public static string $dinamicoPaineis = VIEW . "include/pages/dinamico/paineis/";
    public static string $estatico = VIEW . "include/pages/estatico/";
    public static string $formPost = VIEW."/include/forms/post/";
    public static string $formCreate = VIEW."/include/forms/create/";
    public static string $formEdit = VIEW."/include/forms/edit/";
    public static string $uploadImagemNoticia = "view/img/upload/";


    public function __construct(
        public string $titulo,
        public ?string $base,
        public ?string $main,
        public ?array $model,
    ) {}



    public function renderizar(): void
    {
        
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
