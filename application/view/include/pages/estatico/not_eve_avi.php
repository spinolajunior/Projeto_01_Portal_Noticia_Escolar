<main class="container-fluid py-3 py-md-5">

    <style>
        .painel-conteudos {
            max-width: 1320px;
            margin: 0 auto;
        }

        .cabecalho-conteudos {
            position: relative;
            margin-bottom: 2rem;
        }

        .cabecalho-conteudos h1 {
            font-size: 1.7rem;
            font-weight: 700;
        }

        .btn-voltar {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .card-conteudo {
            --cor-card: #0d6efd;
            position: relative;
            border: 0;
            border-radius: 1rem;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 .125rem .45rem rgba(0, 0, 0, .08);
            transition:
                transform .22s ease,
                box-shadow .22s ease;
        }

        .card-conteudo:hover {
            transform: translateY(-6px);
            box-shadow: 0 .9rem 1.8rem rgba(0, 0, 0, .15);
        }

        .card-conteudo .card-body {
            position: relative;
            overflow: hidden;
        }

        .card-conteudo .card-body::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            right: -90px;
            bottom: -110px;
            background: rgba(255, 255, 255, .08);
            transition: transform .3s ease;
        }

        .card-conteudo:hover .card-body::after {
            transform: scale(1.18);
        }

        .icone-conteudo {
            width: 3.5rem;
            height: 3.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .9rem;
            background: rgba(255, 255, 255, .18);
            font-size: 1.65rem;
            transition:
                transform .22s ease,
                background-color .22s ease;
        }

        .card-conteudo:hover .icone-conteudo {
            transform: scale(1.08) rotate(-3deg);
            background: rgba(255, 255, 255, .26);
        }

        .card-conteudo .card-footer {
            transition: background-color .2s ease;
        }

        .card-conteudo:hover .card-footer {
            background-color: #f8f9fa !important;
        }

        .seta-card {
            display: inline-block;
            transition: transform .22s ease;
        }

        .card-conteudo:hover .seta-card {
            transform: translateX(6px);
        }

        .card-noticia {
            --cor-card: #0d6efd;
        }

        .card-evento {
            --cor-card: #198754;
        }

        .card-aviso {
            --cor-card: #ffc107;
        }

        .titulo-card {
            position: relative;
            z-index: 1;
        }

        .descricao-card {
            position: relative;
            z-index: 1;
            line-height: 1.55;
        }

        @media (max-width: 767.98px) {

            .cabecalho-conteudos {
                text-align: left !important;
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .cabecalho-conteudos h1 {
                font-size: 1.4rem;
            }

            .cabecalho-conteudos p {
                font-size: .9rem;
            }

            .btn-voltar {
                position: static;
                transform: none;
                width: 100%;
                order: 2;
            }

            .card-conteudo {
                border-radius: .85rem;
            }

            .card-conteudo .card-body {
                padding: 1.35rem !important;
            }

            .card-conteudo .card-footer {
                padding: .9rem 1.35rem !important;
            }

            .topo-card {
                margin-bottom: 1.5rem !important;
            }

            .icone-conteudo {
                width: 3rem;
                height: 3rem;
                font-size: 1.4rem;
            }

            .titulo-card {
                font-size: 1.3rem;
            }

            .descricao-card {
                font-size: .9rem;
            }

            .card-conteudo:active {
                transform: scale(.985);
                box-shadow: 0 .3rem .8rem rgba(0, 0, 0, .1);
            }
        }

        @media (hover: none) {

            .card-conteudo:hover {
                transform: none;
                box-shadow: 0 .125rem .45rem rgba(0, 0, 0, .08);
            }

            .card-conteudo:hover .icone-conteudo {
                transform: none;
            }

            .card-conteudo:hover .seta-card {
                transform: none;
            }
        }
    </style>

    <div class="painel-conteudos">

        <header class="cabecalho-conteudos text-center">

            <a
                href="/painel/gerenciamento"
                class="btn btn-outline-secondary btn-voltar"
                onclick="return voltarPainel(event);">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>

            <div>

                <h1 class="text-primary text-uppercase mb-2">
                    <i class="bi bi-grid-fill me-2"></i>
                    Gerenciamento de conteúdos
                </h1>

                <p class="text-body-secondary mb-0">
                    Escolha uma área para administrar as publicações do portal.
                </p>

            </div>

        </header>

        <section
            class="row g-3 g-md-4"
            aria-label="Áreas de gerenciamento">

            <div class="col-12 col-md-4">

                <a
                    href="/painel/noticia"
                    class="card card-conteudo card-noticia h-100 text-decoration-none">

                    <div class="card-body p-4 p-lg-5 bg-primary text-white">

                        <div class="topo-card d-flex align-items-center justify-content-between mb-5">

                            <span class="icone-conteudo">
                                <i class="bi bi-newspaper"></i>
                            </span>

                            <span class="badge rounded-pill text-bg-light text-primary">
                                Publicações
                            </span>

                        </div>

                        <h2 class="h3 titulo-card">
                            Notícias
                        </h2>

                        <p class="mb-0 opacity-75 descricao-card">
                            Crie, edite ou exclua as notícias publicadas no portal.
                        </p>

                    </div>

                    <div class="card-footer bg-white border-0 py-3 px-4 fw-semibold text-primary">

                        Gerenciar notícias

                        <i class="bi bi-arrow-right ms-1 seta-card"></i>

                    </div>

                </a>

            </div>

            <div class="col-12 col-md-4">

                <a
                    href="/painel/evento"
                    class="card card-conteudo card-evento h-100 text-decoration-none">

                    <div class="card-body p-4 p-lg-5 bg-success text-white">

                        <div class="topo-card d-flex align-items-center justify-content-between mb-5">

                            <span class="icone-conteudo">
                                <i class="bi bi-calendar-event"></i>
                            </span>

                            <span class="badge rounded-pill text-bg-light text-success">
                                Agenda escolar
                            </span>

                        </div>

                        <h2 class="h3 titulo-card">
                            Eventos
                        </h2>

                        <p class="mb-0 opacity-75 descricao-card">
                            Crie, edite ou exclua os eventos publicados pela escola.
                        </p>

                    </div>

                    <div class="card-footer bg-white border-0 py-3 px-4 fw-semibold text-success">

                        Gerenciar eventos

                        <i class="bi bi-arrow-right ms-1 seta-card"></i>

                    </div>

                </a>

            </div>

            <div class="col-12 col-md-4">

                <a
                    href="/painel/aviso"
                    class="card card-conteudo card-aviso h-100 text-decoration-none">

                    <div class="card-body p-4 p-lg-5 bg-warning text-dark">

                        <div class="topo-card d-flex align-items-center justify-content-between mb-5">

                            <span
                                class="icone-conteudo"
                                style="background: rgba(255,255,255,.35);">
                                <i class="bi bi-megaphone-fill"></i>
                            </span>

                            <span class="badge rounded-pill text-bg-light text-warning-emphasis">
                                Comunicação
                            </span>

                        </div>

                        <h2 class="h3 titulo-card">
                            Avisos
                        </h2>

                        <p class="mb-0 opacity-75 descricao-card">
                            Crie, edite ou exclua os avisos publicados para a comunidade escolar.
                        </p>

                    </div>

                    <div class="card-footer bg-white border-0 py-3 px-4 fw-semibold text-warning-emphasis">

                        Gerenciar avisos

                        <i class="bi bi-arrow-right ms-1 seta-card"></i>

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