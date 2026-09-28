<main class="container-fluid py-4 py-md-5">
    <style>
        .card-gerenciamento-usuario {
            --cor-destaque: #0d6efd;
            --cor-icone-fundo: #e8eef7;
            --cor-icone: #3d5a80;
            background-color: #fff;
            border: 1px solid #e3eaf4;
            border-radius: 1rem;
            border-top: 5px solid var(--cor-destaque);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .card-gerenciamento-usuario:hover {
            border-color: #86b7fe;
            box-shadow: 0 .75rem 1.5rem rgba(13, 110, 253, .12);
            transform: translateY(-4px);
        }

        .card-gerenciamento-usuario .icone-card {
            align-items: center;
            background: var(--cor-icone-fundo);
            border-radius: .85rem;
            color: var(--cor-icone);
            display: inline-flex;
            font-size: 1.4rem;
            height: 3rem;
            justify-content: center;
            width: 3rem;
        }

        .card-gerenciamento-usuario .card-footer {
            background-color: transparent !important;
        }

        .card-gerenciamento-usuario .link-card {
            color: #0d6efd;
            font-weight: 600;
            text-decoration: none;
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

        .card-editar {
            --cor-destaque: #d68b00;
            --cor-icone-fundo: #fff3cd;
            --cor-icone: #7a4a00;
        }

        .card-remover {
            --cor-destaque: #dc3545;
            --cor-icone-fundo: #f8d7da;
            --cor-icone: #842029;
        }

        .card-visao-geral {
            --cor-destaque: #0dcaf0;
            --cor-icone-fundo: #cff4fc;
            --cor-icone: #055160;
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
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <header class="mb-4 mb-md-5">
                <h1 class="text-primary h2 mb-2 text-uppercase">Área administrativa</h1>
                <p class="text-body-secondary mb-0">Acesse as ferramentas de perfil, contas, permissões e acompanhamento do sistema.</p>
            </header>

            <section class="row g-3 g-md-4" aria-label="Opções de gerenciamento de usuários">
                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="#" class="card card-gerenciamento-usuario card-perfil h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-person-circle" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Editar meu perfil</h2>
                            <p class="text-body-secondary mb-0">Atualize seus próprios dados cadastrais.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="/painel/noticia_evento_aviso" class="card card-gerenciamento-usuario card-publicacoes h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-megaphone" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Gerenciar publicações</h2>
                            <p class="text-body-secondary mb-0">Crie, edite ou exclua notícias, eventos e avisos.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="/cadastro/usuario" class="card card-gerenciamento-usuario card-cadastro h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Cadastrar usuário</h2>
                            <p class="text-body-secondary mb-0">Crie uma nova conta para um usuário do portal.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="#" class="card card-gerenciamento-usuario card-editar h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-person-gear" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Editar usuário</h2>
                            <p class="text-body-secondary mb-0">Altere dados de uma conta, inclusive sua senha.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="#" class="card card-gerenciamento-usuario card-remover h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-person-x" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Remover usuário</h2>
                            <p class="text-body-secondary mb-0">Exclua uma conta que não deve mais acessar o portal.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="#" class="card card-gerenciamento-usuario card-visao-geral h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-people" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Visão geral de usuários</h2>
                            <p class="text-body-secondary mb-0">Consulte e pesquise todas as contas cadastradas.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="#" class="card card-gerenciamento-usuario card-bloqueio h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-lock" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Bloquear ou desbloquear</h2>
                            <p class="text-body-secondary mb-0">Controle se uma conta pode acessar o sistema.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="#" class="card card-gerenciamento-usuario card-logs h-100 text-decoration-none">
                        <div class="card-body p-4"><span class="icone-card mb-4"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                            <h2 class="h5 text-body">Consultar logs</h2>
                            <p class="text-body-secondary mb-0">Acompanhe os registros de atividades realizadas no sistema.</p>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4"><span class="link-card">Acessar <i class="bi bi-arrow-right" aria-hidden="true"></i></span></div>
                    </a>
                </div>
            </section>
        </div>
    </div>
</main>