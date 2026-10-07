<?php

$noticias = $model["noticias"] ?? [];
$destaques = array_values($model["destaques"] ?? []);

$eventos = array_values($model["eventos"] ?? []);
$gruposEventos = array_chunk($eventos, 3);

$avisos = array_values(
    array_filter(
        $model["avisos"] ?? [],
        static fn($aviso) =>
        isset($aviso->ativo) &&
            (int) $aviso->ativo === 1
    )
);

$gruposAvisos = array_chunk($avisos, 3);

$totalPaginas = max(
    0,
    (int) ($model["totalPaginas"] ?? 0)
);

$pagina = max(
    1,
    min(
        (int) ($model["pagina"] ?? 1),
        max(1, $totalPaginas)
    )
);

$formatarDataHome = static function ($data): string {

    if (empty($data)) {
        return "Data não definida";
    }

    try {
        return (new DateTimeImmutable($data))->format("d/m/Y");
    } catch (Exception $e) {
        return "Data não definida";
    }
};

$resumirNoticia = static function ($texto, int $limite): string {

    $texto = trim(
        preg_replace(
            '/\s+/u',
            ' ',
            strip_tags((string) $texto)
        ) ?? ''
    );

    $resumo = mb_substr(
        $texto,
        0,
        $limite,
        "UTF-8"
    );

    if (mb_strlen($texto, "UTF-8") > $limite) {
        $resumo .= "...";
    }

    return htmlspecialchars(
        $resumo,
        ENT_QUOTES,
        "UTF-8"
    );
};

?>

<main class="container my-4 home-portal">

    <style>
        .home-carousel {
            overflow: hidden;
            border-radius: 1rem;
        }

        .home-destaque-item {
            position: relative;
        }

        .home-destaque-imagem {
            width: 100%;
            height: 420px;
            object-fit: cover;
        }

        .home-destaque-item::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(0, 0, 0, .85),
                    rgba(0, 0, 0, .25) 60%,
                    transparent);
            pointer-events: none;
        }

        .home-carousel .carousel-caption {
            z-index: 2;
            left: 10%;
            right: 10%;
            bottom: 2.5rem;
        }

        .home-carousel .carousel-caption h2 {
            font-weight: 700;
        }

        .home-carousel .carousel-control-prev,
        .home-carousel .carousel-control-next {
            z-index: 3;
            width: 8%;
        }

        .home-carousel .carousel-indicators {
            z-index: 3;
        }

        .home-noticia-card {
            border: 0;
            border-radius: .75rem;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .home-noticia-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12) !important;
        }

        .home-noticia-imagem {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .home-noticia-card .card-title,
        .home-noticia-card .card-text {
            overflow-wrap: anywhere;
        }

        .home-sidebar-card {
            position: relative;
            border: 0;
            border-radius: .75rem;
            overflow: hidden;
        }

        .home-sidebar-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            background-color: #0d6efd;
            color: #fff;
            border: 0;
            padding: .9rem 1rem;
            font-weight: 600;
        }

        .home-sidebar-card .list-group-item {
            padding: 1rem;
            border-color: #edf0f4;
        }

        .home-sidebar-card .list-group-item:last-child {
            border-bottom: 0;
        }

        .home-sidebar-card .icone-sidebar {
            color: #0d6efd;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .home-sidebar-card .titulo-sidebar {
            font-size: .9rem;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .home-sidebar-card .data-sidebar {
            font-size: .75rem;
            color: #6c757d;
        }

        .sidebar-controle {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 46px;
            border: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(13, 110, 253, .90);
            color: #fff;
            opacity: 0;
            transition: opacity .2s ease, background-color .2s ease;
        }

        .sidebar-controle:hover {
            background-color: rgba(13, 110, 253, 1);
            color: #fff;
        }

        .sidebar-controle-anterior {
            left: 0;
            border-radius: 0 .5rem .5rem 0;
        }

        .sidebar-controle-proximo {
            right: 0;
            border-radius: .5rem 0 0 .5rem;
        }

        .home-sidebar-card:hover .sidebar-controle {
            opacity: 1;
        }

        .home-sidebar-card .carousel-item {
            transition: transform .4s ease-in-out;
        }

        .sidebar-quantidade {
            font-size: .7rem;
            font-weight: 500;
            padding: .2rem .45rem;
            background-color: rgba(255, 255, 255, .2);
            border-radius: 20px;
        }

        .home-vazio {
            border: 1px dashed #d9e2ef;
            border-radius: .75rem;
            background-color: #f8f9fa;
        }

        .home-vazio .icone-vazio {
            color: #6c757d;
            font-size: 2.5rem;
        }

        @media (max-width: 767.98px) {
            .home-destaque-imagem {
                height: 320px;
            }

            .home-carousel .carousel-caption {
                left: 12%;
                right: 12%;
                bottom: 2rem;
            }

            .home-carousel .carousel-caption h2 {
                font-size: 1.25rem;
            }

            .home-carousel .carousel-caption p {
                font-size: .85rem;
            }

            .home-carousel .carousel-caption small {
                font-size: .75rem;
            }

            .home-carousel .carousel-caption .btn {
                font-size: .8rem;
                padding: .4rem .8rem;
            }

            .home-noticia-imagem {
                height: 200px;
            }
        }

        @media (max-width: 575.98px) {
            .home-destaque-imagem {
                height: 290px;
            }

            .home-carousel .carousel-caption h2 {
                font-size: 1.1rem;
            }

            .home-carousel .carousel-caption p {
                display: none;
            }

            .home-carousel .carousel-caption {
                bottom: 2rem;
            }
        }

        @media (hover: none),
        (max-width: 991.98px) {
            .sidebar-controle {
                opacity: .9;
            }
        }
    </style>

    <section class="mb-5">

        <h2 class="text-center fw-bold mb-3">
            DESTAQUES DA SEMANA
        </h2>

        <?php if (empty($destaques)): ?>

            <div class="home-vazio text-center p-4 p-md-5">
                <i class="bi bi-newspaper icone-vazio d-block mb-3"></i>

                <h5 class="fw-semibold mb-2">
                    Nenhum destaque disponível
                </h5>

                <p class="text-body-secondary mb-0">
                    Ainda não há notícias em destaque no portal.
                </p>
            </div>

        <?php else: ?>

            <div
                id="carouselNoticias"
                class="carousel slide carousel-fade home-carousel shadow-sm"
                data-bs-ride="carousel"
                data-bs-interval="10000">

                <?php if (count($destaques) > 1): ?>

                    <div class="carousel-indicators">

                        <?php foreach ($destaques as $index => $noticia): ?>

                            <button
                                type="button"
                                data-bs-target="#carouselNoticias"
                                data-bs-slide-to="<?= (int) $index ?>"
                                class="<?= $index === 0 ? "active" : "" ?>"
                                aria-label="Destaque <?= $index + 1 ?>"
                                <?= $index === 0 ? 'aria-current="true"' : '' ?>></button>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

                <div class="carousel-inner">

                    <?php foreach ($destaques as $index => $noticia): ?>

                        <div class="carousel-item home-destaque-item <?= $index === 0 ? "active" : "" ?>">

                            <img
                                src="<?= htmlspecialchars(
                                            $noticia->imagem . ".jpg",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                class="d-block home-destaque-imagem"
                                alt="<?= htmlspecialchars(
                                            $noticia->titulo,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>">

                            <div class="carousel-caption text-start">

                                <small class="d-block mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>

                                    Publicado em:
                                    <?= $formatarDataHome($noticia->data_pub) ?>
                                </small>

                                <h2>
                                    <?= htmlspecialchars(
                                        $noticia->titulo,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </h2>

                                <p class="mb-3">
                                    <?= $resumirNoticia(
                                        $noticia->descricao,
                                        90
                                    ) ?>
                                </p>

                                <a
                                    href="/noticias?id=<?= (int) $noticia->id ?>"
                                    class="btn btn-primary">
                                    Leia Mais
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <?php if (count($destaques) > 1): ?>

                    <button
                        class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselNoticias"
                        data-bs-slide="prev"
                        aria-label="Destaque anterior">
                        <span
                            class="carousel-control-prev-icon"
                            aria-hidden="true"></span>
                    </button>

                    <button
                        class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselNoticias"
                        data-bs-slide="next"
                        aria-label="Próximo destaque">
                        <span
                            class="carousel-control-next-icon"
                            aria-hidden="true"></span>
                    </button>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </section>

    <section>

        <h2 class="fw-bold mb-4">
            ÚLTIMAS NOTÍCIAS
        </h2>

        <div class="row g-4 align-items-start">

            <div class="col-12 col-lg-9">

                <?php if (empty($noticias)): ?>

                    <div class="home-vazio text-center p-4 p-md-5">

                        <i class="bi bi-journal-x icone-vazio d-block mb-3"></i>

                        <h5 class="fw-semibold mb-2">
                            Nenhuma notícia disponível
                        </h5>

                        <p class="text-body-secondary mb-0">
                            Ainda não existem notícias publicadas no portal.
                        </p>

                    </div>

                <?php else: ?>

                    <div class="row g-4">

                        <?php foreach ($noticias as $noticia): ?>

                            <div class="col-12 col-md-6 col-xl-4">

                                <article class="card h-100 shadow-sm home-noticia-card">

                                    <img
                                        src="<?= htmlspecialchars(
                                                    $noticia->imagem . ".jpg",
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                ) ?>"
                                        class="card-img-top home-noticia-imagem"
                                        alt="<?= htmlspecialchars(
                                                    $noticia->titulo,
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                ) ?>"
                                        loading="lazy">

                                    <div class="card-body">

                                        <small class="text-primary">

                                            <i class="bi bi-calendar3 me-1"></i>

                                            Publicado em:
                                            <?= $formatarDataHome(
                                                $noticia->data_pub
                                            ) ?>

                                        </small>

                                        <h5 class="card-title mt-2 fw-semibold">

                                            <?= htmlspecialchars(
                                                $noticia->titulo,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>

                                        </h5>

                                        <p class="card-text text-body-secondary">

                                            <?= $resumirNoticia(
                                                $noticia->descricao,
                                                120
                                            ) ?>

                                        </p>

                                    </div>

                                    <div class="card-footer bg-white border-0 pb-3">

                                        <a
                                            href="/noticias?id=<?= (int) $noticia->id ?>"
                                            class="btn btn-primary btn-sm">
                                            Leia Mais
                                            <i class="bi bi-arrow-right ms-1"></i>
                                        </a>

                                    </div>

                                </article>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <?php if ($totalPaginas > 1): ?>

                        <nav class="mt-4" aria-label="Paginação de notícias">

                            <ul class="pagination flex-wrap justify-content-center gap-1">

                                <li class="page-item <?= $pagina <= 1 ? "disabled" : "" ?>">

                                    <?php if ($pagina > 1): ?>

                                        <a
                                            class="page-link"
                                            href="?pagina=<?= $pagina - 1 ?>"
                                            aria-label="Página anterior">
                                            &laquo;
                                        </a>

                                    <?php else: ?>

                                        <span
                                            class="page-link"
                                            aria-disabled="true">
                                            &laquo;
                                        </span>

                                    <?php endif; ?>

                                </li>

                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

                                    <li class="page-item <?= $i === $pagina ? "active" : "" ?>">

                                        <a
                                            class="page-link"
                                            href="?pagina=<?= $i ?>"
                                            <?= $i === $pagina ? 'aria-current="page"' : '' ?>>
                                            <?= $i ?>
                                        </a>

                                    </li>

                                <?php endfor; ?>

                                <li class="page-item <?= $pagina >= $totalPaginas ? "disabled" : "" ?>">

                                    <?php if ($pagina < $totalPaginas): ?>

                                        <a
                                            class="page-link"
                                            href="?pagina=<?= $pagina + 1 ?>"
                                            aria-label="Próxima página">
                                            &raquo;
                                        </a>

                                    <?php else: ?>

                                        <span
                                            class="page-link"
                                            aria-disabled="true">
                                            &raquo;
                                        </span>

                                    <?php endif; ?>

                                </li>

                            </ul>

                        </nav>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <aside class="col-12 col-lg-3">

                <div class="card shadow-sm mb-4 home-sidebar-card">

                    <div class="card-header">

                        <div>
                            <i class="bi bi-calendar-event me-2"></i>
                            Evento Escolar
                        </div>

                        <?php if (count($eventos) > 3): ?>

                            <span class="sidebar-quantidade">
                                <?= count($eventos) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <?php if (empty($eventos)): ?>

                        <div class="card-body text-center py-4">

                            <i class="bi bi-calendar-x fs-2 text-secondary d-block mb-2"></i>

                            <p class="text-body-secondary small mb-0">
                                Nenhum evento cadastrado no momento.
                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            id="carouselEventos"
                            class="carousel slide"
                            data-bs-interval="false"
                            data-bs-touch="true">

                            <div class="carousel-inner">

                                <?php foreach ($gruposEventos as $indice => $grupo): ?>

                                    <div class="carousel-item <?= $indice === 0 ? "active" : "" ?>">

                                        <ul class="list-group list-group-flush">

                                            <?php foreach ($grupo as $evento): ?>

                                                <li class="list-group-item">

                                                    <div class="d-flex align-items-start gap-2">

                                                        <i class="bi bi-calendar-check icone-sidebar"></i>

                                                        <div
                                                            class="flex-grow-1"
                                                            style="min-width: 0;">

                                                            <div class="titulo-sidebar mb-1">

                                                                <?= htmlspecialchars(
                                                                    $evento->titulo,
                                                                    ENT_QUOTES,
                                                                    "UTF-8"
                                                                ) ?>

                                                            </div>

                                                            <div class="data-sidebar">

                                                                <i class="bi bi-clock me-1"></i>

                                                                <?= $formatarDataHome(
                                                                    $evento->data_evento
                                                                ) ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </li>

                                            <?php endforeach; ?>

                                        </ul>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <?php if (count($gruposEventos) > 1): ?>

                                <button
                                    type="button"
                                    class="sidebar-controle sidebar-controle-anterior"
                                    data-bs-target="#carouselEventos"
                                    data-bs-slide="prev"
                                    aria-label="Eventos anteriores">
                                    <i class="bi bi-chevron-left"></i>
                                </button>

                                <button
                                    type="button"
                                    class="sidebar-controle sidebar-controle-proximo"
                                    data-bs-target="#carouselEventos"
                                    data-bs-slide="next"
                                    aria-label="Próximos eventos">
                                    <i class="bi bi-chevron-right"></i>
                                </button>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="card shadow-sm home-sidebar-card">

                    <div class="card-header">

                        <div>
                            <i class="bi bi-megaphone me-2"></i>
                            Avisos
                        </div>

                        <?php if (count($avisos) > 3): ?>

                            <span class="sidebar-quantidade">
                                <?= count($avisos) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <?php if (empty($avisos)): ?>

                        <div class="card-body text-center py-4">

                            <i class="bi bi-bell-slash fs-2 text-secondary d-block mb-2"></i>

                            <p class="text-body-secondary small mb-0">
                                Nenhum aviso disponível no momento.
                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            id="carouselAvisos"
                            class="carousel slide"
                            data-bs-interval="false"
                            data-bs-touch="true">

                            <div class="carousel-inner">

                                <?php foreach ($gruposAvisos as $indice => $grupo): ?>

                                    <div class="carousel-item <?= $indice === 0 ? "active" : "" ?>">

                                        <ul class="list-group list-group-flush">

                                            <?php foreach ($grupo as $aviso): ?>

                                                <li class="list-group-item">

                                                    <div class="d-flex align-items-start gap-2">

                                                        <i class="bi bi-info-circle-fill icone-sidebar"></i>

                                                        <div
                                                            class="flex-grow-1"
                                                            style="min-width: 0;">

                                                            <div class="titulo-sidebar">

                                                                <?= htmlspecialchars(
                                                                    $aviso->titulo,
                                                                    ENT_QUOTES,
                                                                    "UTF-8"
                                                                ) ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </li>

                                            <?php endforeach; ?>

                                        </ul>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <?php if (count($gruposAvisos) > 1): ?>

                                <button
                                    type="button"
                                    class="sidebar-controle sidebar-controle-anterior"
                                    data-bs-target="#carouselAvisos"
                                    data-bs-slide="prev"
                                    aria-label="Avisos anteriores">
                                    <i class="bi bi-chevron-left"></i>
                                </button>

                                <button
                                    type="button"
                                    class="sidebar-controle sidebar-controle-proximo"
                                    data-bs-target="#carouselAvisos"
                                    data-bs-slide="next"
                                    aria-label="Próximos avisos">
                                    <i class="bi bi-chevron-right"></i>
                                </button>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </aside>

        </div>

    </section>

</main>