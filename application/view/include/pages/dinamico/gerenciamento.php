<main class="container-fluid py-3 py-md-5">

    <style>
        .painel-administrativo {
            max-width: 1320px;
            margin: 0 auto;
        }

        .cabecalho-administrativo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .cabecalho-administrativo h1 {
            font-size: 1.7rem;
            font-weight: 700;
            margin: 0;
        }

        .cabecalho-administrativo p {
            margin: .35rem 0 0;
        }

        .card-gerenciamento-usuario {
            --cor-destaque: #0d6efd;
            --cor-icone-fundo: #e8eef7;
            --cor-icone: #3d5a80;

            position: relative;
            background-color: #fff;
            border: 1px solid #e3eaf4;
            border-top: 5px solid var(--cor-destaque);
            border-radius: 1rem;
            overflow: hidden;
            transition:
                transform .22s ease,
                box-shadow .22s ease,
                border-color .22s ease;
        }

        .card-gerenciamento-usuario::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg,
                    transparent 55%,
                    rgba(13, 110, 253, .025));
            opacity: 0;
            transition: opacity .22s ease;
            pointer-events: none;
        }

        .card-gerenciamento-usuario:hover {
            border-color: var(--cor-destaque);
            box-shadow: 0 .75rem 1.6rem rgba(0, 0, 0, .11);
            transform: translateY(-5px);
        }

        .card-gerenciamento-usuario:hover::before {
            opacity: 1;
        }

        .card-gerenciamento-usuario:focus-visible {
            outline: 3px solid rgba(13, 110, 253, .25);
            outline-offset: 3px;
        }

        .card-gerenciamento-usuario .icone-card {
            align-items: center;
            background-color: var(--cor-icone-fundo);
            border-radius: .85rem;
            color: var(--cor-icone);
            display: inline-flex;
            font-size: 1.4rem;
            height: 3rem;
            justify-content: center;
            width: 3rem;
            transition:
                transform .22s ease,
                box-shadow .22s ease;
        }

        .card-gerenciamento-usuario:hover .icone-card {
            transform: scale(1.08) rotate(-2deg);
            box-shadow: 0 .35rem .8rem rgba(0, 0, 0, .08);
        }

        .card-gerenciamento-usuario .card-body,
        .card-gerenciamento-usuario .card-footer {
            position: relative;
            z-index: 1;
        }

        .card-gerenciamento-usuario .card-footer {
            background-color: transparent !important;
        }

        .card-gerenciamento-usuario .link-card {
            color: var(--cor-destaque);
            font-weight: 600;
            text-decoration: none;
        }

        .card-gerenciamento-usuario .link-card i {
            transition: transform .22s ease;
        }

        .card-gerenciamento-usuario:hover .link-card i {
            transform: translateX(5px);
        }

        .card-perfil {
            --cor-destaque: #0d6efd;
            --cor-icone-fundo: #dbeafe;
            --cor-icone: #084298;
        }

        .card-publicacoes {
            --cor-destaque: #198754;
            --cor-icone-fundo: #d1e7dd;
            --cor-icone: #0f5132;
        }

        .card-cadastro {
            --cor-destaque: #6f42c1;
            --cor-icone-fundo: #e9d8fd;
            --cor-icone: #432874;
        }

        .card-gerenciar-contas {
            --cor-destaque: #d68b00;
            --cor-icone-fundo: #fff3cd;
            --cor-icone: #7a4a00;
        }

        .card-bloqueio {
            --cor-destaque: #795548;
            --cor-icone-fundo: #efe3d0;
            --cor-icone: #5d4037;
        }

        .card-logs {
            --cor-destaque: #6c757d;
            --cor-icone-fundo: #e2e3e5;
            --cor-icone: #343a40;
        }

        @media (max-width: 767.98px) {
            .cabecalho-administrativo {
                flex-direction: column;
                align-items: stretch;
                margin-bottom: 1.5rem;
            }

            .cabecalho-administrativo h1 {
                font-size: 1.4rem;
            }

            .cabecalho-administrativo .btn {
                width: 100%;
            }

            .card-gerenciamento-usuario {
                border-radius: .85rem;
            }

            .card-gerenciamento-usuario .card-body {
                padding: 1.25rem !important;
            }

            .card-gerenciamento-usuario .card-footer {
                padding: 0 1.25rem 1.25rem !important;
            }

            .card-gerenciamento-usuario .icone-card {
                width: 2.7rem;
                height: 2.7rem;
                font-size: 1.25rem;
                margin-bottom: 1rem !important;
            }

            .card-gerenciamento-usuario h2 {
                font-size: 1rem;
                margin-bottom: .45rem;
            }

            .card-gerenciamento-usuario p {
                font-size: .88rem;
                line-height: 1.45;
            }

            .card-gerenciamento-usuario:active {
                transform: scale(.985);
                box-shadow: 0 .3rem .75rem rgba(0, 0, 0, .08);
            }
        }

        @media (max-width: 575.98px) {
            .painel-administrativo {
                padding-left: .15rem;
                padding-right: .15rem;
            }

            .opcoes-gerenciamento {
                --bs-gutter-y: .85rem;
            }

            .card-gerenciamento-usuario {
                border-top-width: 4px;
            }
        }

        @media (hover: none) {
            .card-gerenciamento-usuario:hover {
                transform: none;
                box-shadow: none;
            }

            .card-gerenciamento-usuario:hover .icone-card {
                transform: none;
                box-shadow: none;
            }

            .card-gerenciamento-usuario:hover .link-card i {
                transform: none;
            }
        }
    </style>

    <div class="painel-administrativo">

        <header class="cabecalho-administrativo">

            <div>
                <h1 class="text-primary text-uppercase">
                    <i class="bi bi-grid-fill me-2"></i>
                    Área administrativa
                </h1>

                <p class="text-body-secondary">
                    Acesse as ferramentas de perfil, contas, permissões e acompanhamento do sistema.
                </p>
            </div>

            <a
                href="/painel/gerenciamento"
                class="btn btn-outline-secondary"
                onclick="return voltarPainel(event);">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>

        </header>

        <section
            class="row g-3 g-md-4 opcoes-gerenciamento"
            aria-label="Opções de gerenciamento de usuários">

            <div class="col-12 col-sm-6 col-xl-4">

                <a
                    href="/atualizar/myself"
                    class="card card-gerenciamento-usuario card-perfil h-100 text-decoration-none">

                    <div class="card-body p-4">

                        <span class="icone-card mb-4">
                            <i class="bi bi-person-circle"></i>
                        </span>

                        <h2 class="h5 text-body">
                            Editar meu perfil
                        </h2>

                        <p class="text-body-secondary mb-0">
                            Atualize seus próprios dados cadastrais.
                        </p>

                    </div>

                    <div class="card-footer border-0 px-4 pb-4">

                        <span class="link-card">
                            Acessar
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </a>

            </div>

            <div class="col-12 col-sm-6 col-xl-4">

                <a
                    href="/painel/noticia_evento_aviso"
                    class="card card-gerenciamento-usuario card-publicacoes h-100 text-decoration-none">

                    <div class="card-body p-4">

                        <span class="icone-card mb-4">
                            <i class="bi bi-megaphone"></i>
                        </span>

                        <h2 class="h5 text-body">
                            Gerenciar publicações
                        </h2>

                        <p class="text-body-secondary mb-0">
                            Crie, edite ou exclua notícias, eventos e avisos.
                        </p>

                    </div>

                    <div class="card-footer border-0 px-4 pb-4">

                        <span class="link-card">
                            Acessar
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </a>

            </div>

            <div class="col-12 col-sm-6 col-xl-4">

                <a
                    href="/cadastro/usuario"
                    class="card card-gerenciamento-usuario card-cadastro h-100 text-decoration-none">

                    <div class="card-body p-4">

                        <span class="icone-card mb-4">
                            <i class="bi bi-person-plus"></i>
                        </span>

                        <h2 class="h5 text-body">
                            Cadastrar usuário
                        </h2>

                        <p class="text-body-secondary mb-0">
                            Crie uma nova conta para um usuário do portal.
                        </p>

                    </div>

                    <div class="card-footer border-0 px-4 pb-4">

                        <span class="link-card">
                            Acessar
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </a>

            </div>

            <div class="col-12 col-sm-6 col-xl-4">

                <a
                    href="/painel/usuarios"
                    class="card card-gerenciamento-usuario card-gerenciar-contas h-100 text-decoration-none">

                    <div class="card-body p-4">

                        <span class="icone-card mb-4">
                            <i class="bi bi-person-gear"></i>
                        </span>

                        <h2 class="h5 text-body">
                            Gerenciar usuários
                        </h2>

                        <p class="text-body-secondary mb-0">
                            Visualize e gerencie as contas cadastradas no sistema.
                        </p>

                    </div>

                    <div class="card-footer border-0 px-4 pb-4">

                        <span class="link-card">
                            Gerenciar contas
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </a>

            </div>

            <div class="col-12 col-sm-6 col-xl-4">

                <a
                    href="/painel/acesso/usuario"
                    class="card card-gerenciamento-usuario card-bloqueio h-100 text-decoration-none">

                    <div class="card-body p-4">

                        <span class="icone-card mb-4">
                            <i class="bi bi-lock"></i>
                        </span>

                        <h2 class="h5 text-body">
                            Bloquear ou desbloquear
                        </h2>

                        <p class="text-body-secondary mb-0">
                            Controle se uma conta pode acessar o sistema.
                        </p>

                    </div>

                    <div class="card-footer border-0 px-4 pb-4">

                        <span class="link-card">
                            Acessar
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </a>

            </div>

            <div class="col-12 col-sm-6 col-xl-4">

                <a
                    href="/painel/logs"
                    class="card card-gerenciamento-usuario card-logs h-100 text-decoration-none">

                    <div class="card-body p-4">

                        <span class="icone-card mb-4">
                            <i class="bi bi-journal-text"></i>
                        </span>

                        <h2 class="h5 text-body">
                            Consultar logs
                        </h2>

                        <p class="text-body-secondary mb-0">
                            Acompanhe os registros de atividades realizadas no sistema.
                        </p>

                    </div>

                    <div class="card-footer border-0 px-4 pb-4">

                        <span class="link-card">
                            Acessar
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </a>

            </div>

        </section>

    </div>

</main>

<script>
    function voltarPainel(event) {

        if (window.history.length > 1) {
            event.preventDefault();
            window.history.back();
            return false;
        }

        return true;
    }
</script>