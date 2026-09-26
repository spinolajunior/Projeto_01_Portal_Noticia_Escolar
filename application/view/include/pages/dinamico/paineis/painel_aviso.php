<?php

$avisos = $model["avisos"] ?? [];

?>

<main class="container-fluid py-3 py-md-4">

    <style>
        /* ====================================
           TABELA DESKTOP
        ==================================== */

        .tabela-gerenciamento thead th {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
            font-weight: 600;
        }

        .tabela-gerenciamento tbody td {
            background-color: #fff;
        }

        .tabela-gerenciamento th:not(:last-child),
        .tabela-gerenciamento td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .tabela-gerenciamento thead th:not(:last-child) {
            border-right-color: rgba(255, 255, 255, .35);
        }

        .tabela-gerenciamento tbody tr {
            transition: box-shadow .18s ease;
        }

        .tabela-gerenciamento tbody tr:hover {
            box-shadow: inset 4px 0 0 #0d6efd;
        }

        .tabela-gerenciamento tbody tr:hover td {
            background-color: #eef5ff;
        }

        /* ====================================
           BOTÕES
        ==================================== */

        .acao-tabela {
            border-radius: .45rem;
            text-decoration: none;
            transition: all .18s ease;
        }

        .acao-tabela:hover {
            transform: translateY(-1px);
        }

        /* ====================================
           STATUS
        ==================================== */

        .status-aviso {
            font-size: .75rem;
            font-weight: 600;
            padding: .45rem .75rem;
            min-width: 85px;
            display: inline-block;
        }

        /* ====================================
           CARDS MOBILE
        ==================================== */

        .card-aviso-mobile {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            background-color: #fff;
            transition: box-shadow .2s ease;
        }

        .card-aviso-mobile:hover {
            box-shadow: 0 .35rem 1rem rgba(0, 0, 0, .08);
        }

        .card-aviso-mobile .card-header {
            background-color: #0d6efd;
            color: #fff;
            border: 0;
        }

        .card-aviso-mobile .titulo-aviso {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .card-aviso-mobile .info-label {
            color: #6c757d;
            font-size: .78rem;
            margin-bottom: .2rem;
        }

        .card-aviso-mobile .info-valor {
            font-size: .9rem;
            overflow-wrap: anywhere;
        }

        .card-aviso-mobile .icone-info {
            width: 22px;
            flex-shrink: 0;
            color: #0d6efd;
        }

        .card-aviso-mobile .acoes-mobile .btn {
            min-width: 0;
            padding: .6rem .4rem;
        }

        /* ====================================
           TELAS PEQUENAS
        ==================================== */

        @media (max-width: 575.98px) {

            .cabecalho-painel .btn {
                width: 100%;
            }

            .card-aviso-mobile .card-body {
                padding: 1rem;
            }

        }
    </style>

    <div class="row justify-content-center">

        <div class="col-12 col-xxl-10">

            <!-- ====================================
                 CABEÇALHO
            ==================================== -->

            <div class="cabecalho-painel d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">

                <div>

                    <h1 class="h3 mb-1">
                        Avisos
                    </h1>

                    <p class="text-body-secondary mb-0">
                        Gerencie os avisos publicados no portal.
                    </p>

                </div>

                <a
                    href="/cadastro/aviso"
                    class="btn btn-primary">

                    <i class="bi bi-plus-circle me-1"></i>
                    Novo Aviso

                </a>

            </div>


            <?php if (empty($avisos)): ?>

                <!-- NENHUM AVISO -->

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="bi bi-megaphone fs-1 text-secondary d-block mb-3"></i>

                        <h5 class="mb-2">
                            Nenhum aviso cadastrado
                        </h5>

                        <p class="text-body-secondary mb-0">
                            Cadastre um aviso para começar a gerenciar os comunicados.
                        </p>

                    </div>

                </div>

            <?php else: ?>


                <!-- ====================================
                     LAYOUT DESKTOP
                ==================================== -->

                <div class="d-none d-lg-block">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0 tabela-gerenciamento">

                                    <thead>

                                        <tr>

                                            <th scope="col" class="ps-3 ps-md-4">
                                                ID
                                            </th>

                                            <th scope="col">
                                                Título
                                            </th>

                                            <th scope="col" class="text-center">
                                                Status
                                            </th>

                                            <th scope="col" class="pe-3 pe-md-4 text-center">
                                                Ações
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach ($avisos as $aviso): ?>

                                            <tr>

                                                <!-- ID -->
                                                <td class="ps-3 ps-md-4">

                                                    <?= (int) $aviso->id ?>

                                                </td>

                                                <!-- TÍTULO -->
                                                <td style="min-width: 180px; overflow-wrap: anywhere;">

                                                    <?= htmlspecialchars($aviso->titulo, ENT_QUOTES, 'UTF-8') ?>

                                                </td>

                                                <!-- STATUS -->
                                                <td class="text-center text-nowrap">

                                                    <?php if ($aviso->ativo === null): ?>

                                                        <span class="badge rounded-pill bg-secondary status-aviso">

                                                            Não definido

                                                        </span>

                                                    <?php elseif ((int) $aviso->ativo === 1): ?>

                                                        <span class="badge rounded-pill bg-success status-aviso">

                                                            <i class="bi bi-check-circle me-1"></i>
                                                            Ativo

                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge rounded-pill bg-danger status-aviso">

                                                            <i class="bi bi-x-circle me-1"></i>
                                                            Inativo

                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- AÇÕES -->
                                                <td class="pe-3 pe-md-4 text-center text-nowrap">

                                                    <a
                                                        href="/atualizar/aviso?id=<?= (int) $aviso->id ?>"
                                                        class="btn btn-sm btn-outline-primary acao-tabela me-1">

                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        Editar

                                                    </a>

                                                    <a
                                                        href="/excluir/aviso?id=<?= (int) $aviso->id ?>"
                                                        class="btn btn-sm btn-outline-danger acao-tabela"
                                                        onclick="return confirm('Deseja realmente excluir este aviso?');">

                                                        <i class="bi bi-trash3 me-1"></i>
                                                        Excluir

                                                    </a>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ====================================
                     LAYOUT MOBILE / TABLET
                ==================================== -->

                <div class="d-lg-none">

                    <div class="row g-3">

                        <?php foreach ($avisos as $aviso): ?>

                            <div class="col-12 col-md-6">

                                <article class="card card-aviso-mobile h-100 shadow-sm">

                                    <!-- CABEÇALHO DO CARD -->
                                    <div class="card-header p-3">

                                        <div class="d-flex justify-content-between align-items-center gap-2">

                                            <span class="fw-semibold">

                                                <i class="bi bi-megaphone me-1"></i>

                                                Aviso #<?= (int) $aviso->id ?>

                                            </span>


                                            <!-- STATUS -->
                                            <?php if ($aviso->ativo === null): ?>

                                                <span class="badge rounded-pill bg-secondary status-aviso">

                                                    Não definido

                                                </span>

                                            <?php elseif ((int) $aviso->ativo === 1): ?>

                                                <span class="badge rounded-pill bg-success status-aviso">

                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Ativo

                                                </span>

                                            <?php else: ?>

                                                <span class="badge rounded-pill bg-danger status-aviso">

                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Inativo

                                                </span>

                                            <?php endif; ?>

                                        </div>

                                    </div>


                                    <!-- CONTEÚDO -->
                                    <div class="card-body d-flex flex-column">

                                        <!-- TÍTULO -->
                                        <h2 class="titulo-aviso h6 mb-3">

                                            <?= htmlspecialchars($aviso->titulo, ENT_QUOTES, 'UTF-8') ?>

                                        </h2>

                                        <hr class="my-2">


                                        <!-- SITUAÇÃO -->
                                        <div class="d-flex align-items-start gap-2 mb-3 mt-2">

                                            <i class="bi bi-info-circle icone-info fs-5"></i>

                                            <div class="flex-grow-1" style="min-width: 0;">

                                                <div class="info-label">
                                                    Situação do aviso
                                                </div>

                                                <div class="info-valor fw-medium">

                                                    <?php if ($aviso->ativo === null): ?>

                                                        Não definida

                                                    <?php elseif ((int) $aviso->ativo === 1): ?>

                                                        Ativo

                                                    <?php else: ?>

                                                        Inativo

                                                    <?php endif; ?>

                                                </div>

                                            </div>

                                        </div>


                                        <!-- BOTÕES -->
                                        <div class="mt-auto pt-3 border-top">

                                            <div class="row g-2 acoes-mobile">

                                                <!-- EDITAR -->
                                                <div class="col-6">

                                                    <a
                                                        href="/atualizar/aviso?id=<?= (int) $aviso->id ?>"
                                                        class="btn btn-outline-primary w-100 acao-tabela">

                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        Editar

                                                    </a>

                                                </div>


                                                <!-- EXCLUIR -->
                                                <div class="col-6">

                                                    <a
                                                        href="/excluir/aviso?id=<?= (int) $aviso->id ?>"
                                                        class="btn btn-outline-danger w-100 acao-tabela"
                                                        onclick="return confirm('Deseja realmente excluir este aviso?');">

                                                        <i class="bi bi-trash3 me-1"></i>
                                                        Excluir

                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </article>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</main>