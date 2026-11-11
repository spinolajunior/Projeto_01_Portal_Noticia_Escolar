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
    public static string $uploadImagemNoticia = BASE_DIR . "view/img/upload/";


    public function __construct(
        public string $titulo,
        public ?string $base,
        public ?string $main,
        public ?array $model,
    ){}



    public static function csrf(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
    }

    public function renderizar(): void
    {
        
        $titulo = $this->titulo;
        $nonce = $GLOBALS['nonce'] ?? '';

        ob_start();

        $model = $this->model;

        $flash = $_SESSION['flash_msg'] ?? null;
        if($flash !== null){
            unset($_SESSION['flash_msg']);
        }

        if (file_exists($this->main)) {
            include $this->main;
        } else {
            throw new \RuntimeException('View de conteúdo não encontrada.');
        }

        $content = ob_get_clean();

        if (file_exists($this->base)) {

            include $this->base;
        } else {
            throw new \RuntimeException('Template não encontrado.');
        }
    }
}
