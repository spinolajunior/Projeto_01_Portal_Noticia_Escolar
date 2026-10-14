<?php

namespace view;

use Exception;

class Flash_msg
{

    private array $_2xx = [
        200 => "Operação realizada com sucesso.",
        201 => "Cadastro realizado com sucesso.",
        202 => "Alteração realizada com sucesso.",
        203 => "Registro excluído com sucesso.",
        204 => "Login realizado com sucesso.",
        205 => "Logout realizado com sucesso.",
        206 => "Dados salvos com sucesso.",
        207 => "Status alterado com sucesso.",
        208 => "Perfil atualizado com sucesso.",
    ];


    private array $_3xx = [
        300 => "Nenhuma alteração foi realizada.",
        301 => "Nenhum registro foi encontrado.",
        302 => "Você já está autenticado.",
        303 => "Sua sessão foi encerrada.",
        304 => "O registro informado já existe.",
        305 => "Não existem notícias cadastradas.",
        306 => "Não existem eventos cadastrados.",
        307 => "Não existem avisos cadastrados.",
        308 => "Não existem usuários cadastrados.",
    ];


    private array $_4xx = [
        400 => "Os dados informados são inválidos.",
        401 => "Usuário ou senha incorretos.",
        402 => "Usuário desativado.",
        403 => "Você não possui permissão para realizar esta operação.",
        404 => "Registro não encontrado.",
        405 => "Método de requisição não permitido.",
        406 => "Preencha todos os campos obrigatórios.",
        407 => "As senhas informadas não coincidem.",
        408 => "A operação não pôde ser concluída.",
        409 => "Já existe um registro com os dados informados.",
        410 => "Este registro não está mais disponível.",
        411 => "Usuário não encontrado.",
        412 => "Notícia não encontrada.",
        413 => "Evento não encontrado.",
        414 => "Aviso não encontrado.",
        415 => "Formato de arquivo não permitido.",
        416 => "A imagem enviada é inválida.",
        417 => "Matrícula já cadastrada.",
        418 => "Nome de usuário já está em uso.",
        419 => "Sua sessão expirou. Faça login novamente.",
        420 => "Não foi possível excluir este registro.",
        421 => "Não foi possível atualizar este registro.",
        422 => "Os dados enviados não puderam ser processados.",
        423 => "Este registro está bloqueado.",
        424 => "Não foi possível concluir a operação solicitada.",
        425 => "A operação não pode ser realizada neste momento.",
        426 => "Ação não permitida para este usuário.",
        427 => "Credenciais inválidas.",
        428 => "É necessário confirmar os dados antes de continuar.",
        429 => "Muitas tentativas realizadas. Tente novamente posteriormente.",
    ];


    private array $_5xx = [
        500 => "Ocorreu um erro interno no sistema.",
        501 => "Não foi possível realizar esta operação.",
        502 => "Ocorreu um erro ao acessar o banco de dados.",
        503 => "Serviço temporariamente indisponível.",
        504 => "Não foi possível salvar os dados.",
        505 => "Não foi possível atualizar os dados.",
        506 => "Não foi possível excluir os dados.",
        507 => "Não foi possível carregar os dados.",
        508 => "Ocorreu um erro durante a autenticação.",
        509 => "Ocorreu um erro durante o upload do arquivo.",
        510 => "Não foi possível processar os dados solicitados.",
        511 => "Ocorreu um erro ao iniciar a sessão.",
        512 => "Ocorreu um erro inesperado no sistema.",
    ];

    private string $flash_msg;
    private string $tipo;
    public function __construct(int $cod_msg, ?string $msg = null)
    {
        if ($cod_msg >= 200 && $cod_msg <= 208) {
            $this->flash_msg = $this->_2xx[$cod_msg] . ($msg !== null ? " $msg" : '');
            $this->tipo = "success";
        } elseif ($cod_msg >= 300 && $cod_msg <= 308) {
            $this->flash_msg = $this->_3xx[$cod_msg] . ($msg !== null ? " $msg" : '');
            $this->tipo = "warning";
        } elseif ($cod_msg >= 400 && $cod_msg <= 429) {
            $this->flash_msg = $this->_4xx[$cod_msg] . ($msg !== null ? " $msg" : '');
            $this->tipo = "danger";
        } elseif ($cod_msg >= 500 && $cod_msg <= 512) {
            $this->flash_msg = $this->_5xx[$cod_msg] . ($msg !== null ? " $msg" : '');
            $this->tipo = "danger";
        } elseif ($cod_msg === 0) {
            $this->flash_msg =''.($msg !== null ? " $msg" : '');
            $this->tipo = "danger";
        } else {
            throw new Exception("$cod_msg invalido!");
        }
    }


    //Esta função cria um valor na session com uma mensagem , essa mensagerem sera consumida pela view!.
    public function flashMsg(): void
    {
        $_SESSION['flash_msg'] = [
            "msg" => $this->flash_msg,
            "tipo" => $this->tipo
        ];
    }
}
