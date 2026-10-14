<?php
$listaHome = static function ($dados): array {
    if (is_array($dados)) return array_values($dados);
    if ($dados instanceof Traversable) return array_values(iterator_to_array($dados));
    return [];
};

$noticias = array_values(array_filter(
    $listaHome($model['noticias'] ?? []),
    static fn($n) => is_object($n) && (!isset($n->status) || (int)$n->status === 1)
));

$destaques = array_slice(array_values(array_filter(
    $listaHome($model['destaques'] ?? []),
    static fn($n) => is_object($n) && (!isset($n->status) || (int)$n->status === 1)
)), 0, 3);

$eventos = array_values(array_filter(
    $listaHome($model['eventos'] ?? []),
    static fn($e) => is_object($e)
));

$avisos = array_values(array_filter(
    $listaHome($model['avisos'] ?? []),
    static fn($a) => is_object($a) && (!isset($a->ativo) || (int)$a->ativo === 1)
));

$gruposEventos = array_chunk($eventos, 3);
$gruposAvisos = array_chunk($avisos, 3);

$totalPaginas = max(0, (int)($model['totalPaginas'] ?? 0));
$pagina = max(1, min((int)($model['pagina'] ?? 1), max(1, $totalPaginas)));

$esc = static fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$formatarDataHome = static function ($data): string {
    if (empty($data)) return 'Data não definida';
    try {
        return (new DateTimeImmutable((string)$data))->format('d/m/Y');
    } catch (Exception $e) {
        return 'Data não definida';
    }
};

$resumirNoticia = static function ($texto, int $limite): string {
    $texto = trim(preg_replace('/\s+/u', ' ', strip_tags((string)$texto)) ?? '');
    $resumo = mb_substr($texto, 0, $limite, 'UTF-8');
    if (mb_strlen($texto, 'UTF-8') > $limite) $resumo .= '...';
    return htmlspecialchars($resumo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$imagemHome = static function ($caminho): string {
    $caminho = trim((string)$caminho);
    if ($caminho === '') return '';
    if (preg_match('~^https?://~i', $caminho)) return $caminho;
    return '/' . ltrim(str_replace('\\', '/', $caminho), '/');
};

$semNoticias = empty($noticias);
?>
<main class="container my-4 home-portal">
    <style>
        .home-carousel {
            overflow: hidden;
            border-radius: 1rem
        }

        .home-destaque-item {
            position: relative;
            background: linear-gradient(135deg, #0d6efd, #17365c)
        }

        .home-destaque-imagem {
            width: 100%;
            height: 420px;
            object-fit: cover
        }

        .home-destaque-item::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .85), rgba(0, 0, 0, .25) 60%, transparent);
            pointer-events: none
        }

        .home-carousel .carousel-caption {
            z-index: 2;
            left: 10%;
            right: 10%;
            bottom: 2.5rem
        }

        .home-carousel .carousel-caption h2 {
            font-weight: 700;
            overflow-wrap: anywhere
        }

        .home-carousel .carousel-control-prev,
        .home-carousel .carousel-control-next {
            z-index: 3;
            width: 8%
        }

        .home-carousel .carousel-indicators {
            z-index: 3
        }

        .home-noticia-card {
            border: 0;
            border-radius: .75rem;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s
        }

        .home-noticia-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12) !important
        }

        .home-noticia-media {
            height: 180px;
            background: #e9eef5;
            overflow: hidden;
            position: relative
        }

        .home-noticia-imagem {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .home-noticia-placeholder {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #8794a5;
            gap: 6px
        }

        .home-noticia-card .card-title,
        .home-noticia-card .card-text {
            overflow-wrap: anywhere
        }

        .home-noticia-card .card-title {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden
        }

        .home-noticia-card .card-text {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden
        }

        .home-sidebar-card {
            position: relative;
            border: 0;
            border-radius: .75rem;
            overflow: hidden
        }

        .home-sidebar-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            background: #0d6efd;
            color: #fff;
            border: 0;
            padding: .9rem 1rem;
            font-weight: 600
        }

        .home-sidebar-card .list-group-item {
            padding: 1rem;
            border-color: #edf0f4
        }

        .home-sidebar-card .list-group-item:last-child {
            border-bottom: 0
        }

        .home-sidebar-card .icone-sidebar {
            color: #0d6efd;
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .home-sidebar-card .titulo-sidebar {
            font-size: .9rem;
            font-weight: 600;
            overflow-wrap: anywhere
        }

        .home-sidebar-card .data-sidebar {
            font-size: .75rem;
            color: #6c757d
        }

        .home-sidebar-card .carousel-item {
            transition: transform .4s ease-in-out
        }

        .sidebar-quantidade {
            font-size: .7rem;
            font-weight: 500;
            padding: .2rem .45rem;
            background: rgba(255, 255, 255, .2);
            border-radius: 20px
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
            background: rgba(13, 110, 253, .9);
            color: #fff;
            opacity: 0;
            transition: opacity .2s, background .2s
        }

        .sidebar-controle:hover {
            background: #0d6efd;
            color: #fff
        }

        .sidebar-controle-anterior {
            left: 0;
            border-radius: 0 .5rem .5rem 0
        }

        .sidebar-controle-proximo {
            right: 0;
            border-radius: .5rem 0 0 .5rem
        }

        .home-sidebar-card:hover .sidebar-controle,
        .home-sidebar-card:focus-within .sidebar-controle {
            opacity: 1
        }

        .home-vazio {
            border: 1px dashed #d9e2ef;
            border-radius: .75rem;
            background: #f8f9fa
        }

        .home-vazio .icone-vazio {
            color: #6c757d;
            font-size: 2.5rem
        }

        .home-portal .pagination .page-link {
            border-radius: 7px
        }

        @media(max-width:767.98px) {
            .home-destaque-imagem {
                height: 320px
            }

            .home-carousel .carousel-caption {
                left: 12%;
                right: 12%;
                bottom: 2rem
            }

            .home-carousel .carousel-caption h2 {
                font-size: 1.25rem
            }

            .home-carousel .carousel-caption p {
                font-size: .85rem
            }

            .home-carousel .carousel-caption small {
                font-size: .75rem
            }

            .home-carousel .carousel-caption .btn {
                font-size: .8rem;
                padding: .4rem .8rem
            }

            .home-noticia-media {
                height: 200px
            }
        }

        @media(max-width:575.98px) {
            .home-destaque-imagem {
                height: 290px
            }

            .home-carousel .carousel-caption h2 {
                font-size: 1.1rem
            }

            .home-carousel .carousel-caption p {
                display: none
            }

            .home-carousel .carousel-caption {
                bottom: 2rem
            }

            .home-sidebar-card {
                margin-bottom: 1rem !important
            }
        }

        @media(hover:none), (max-width:991.98px) {
            .sidebar-controle {
                opacity: .9
            }
        }
    </style>

    <?php if (!empty($destaques)): ?>
        <section class="mb-5" id="destaques">
            <h2 class="text-center fw-bold mb-3">DESTAQUES DA SEMANA</h2>
            <div id="carouselNoticias" class="carousel slide carousel-fade home-carousel shadow-sm" data-bs-ride="carousel" data-bs-interval="10000">
                <?php if (count($destaques) > 1): ?>
                    <div class="carousel-indicators">
                        <?php foreach ($destaques as $index => $noticia): ?>
                            <button type="button" data-bs-target="#carouselNoticias" data-bs-slide-to="<?= (int)$index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Destaque <?= $index + 1 ?>" <?= $index === 0 ? 'aria-current="true"' : '' ?>></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="carousel-inner">
                    <?php foreach ($destaques as $index => $noticia): ?>
                        <div class="carousel-item home-destaque-item <?= $index === 0 ? 'active' : '' ?>">
                            <?php $urlImagem = $imagemHome($noticia->imagem ?? ''); ?>
                            <?php if ($urlImagem !== ''): ?>
                                <img src="<?= $esc($urlImagem) ?>" class="d-block home-destaque-imagem" alt="<?= $esc($noticia->titulo ?? '') ?>" onerror="this.style.visibility='hidden';">
                            <?php else: ?>
                                <div class="home-destaque-imagem"></div>
                            <?php endif; ?>
                            <div class="carousel-caption text-start">
                                <small class="d-block mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Publicado em: <?= $formatarDataHome($noticia->data_pub ?? null) ?>
                                </small>
                                <h2><?= $esc($noticia->titulo ?? '') ?></h2>
                                <p class="mb-3"><?= $resumirNoticia($noticia->descricao ?? '', 90) ?></p>
                                <a href="/noticias?id=<?= (int)($noticia->id ?? 0) ?>" class="btn btn-primary">
                                    Leia Mais <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($destaques) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselNoticias" data-bs-slide="prev" aria-label="Destaque anterior">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselNoticias" data-bs-slide="next" aria-label="Próximo destaque">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="mb-4">
        <?php if (!$semNoticias): ?>
            <h2 class="fw-bold mb-4" id="ultimas-noticias">ÚLTIMAS NOTÍCIAS</h2>
        <?php endif; ?>

        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-9 <?= $semNoticias ? 'order-2 order-lg-1' : '' ?>">

                <?php if ($semNoticias): ?>
                    <div id="ultimas-noticias">
                        <h2 class="fw-bold mb-4">ÚLTIMAS NOTÍCIAS</h2>
                        <div class="home-vazio text-center p-4 p-md-5">
                            <i class="bi bi-journal-x icone-vazio d-block mb-3"></i>
                            <h5 class="fw-semibold mb-2">Nenhuma notícia disponível</h5>
                            <p class="text-body-secondary mb-0">Ainda não existem notícias ativas publicadas no portal.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($noticias as $noticia): ?>
                            <?php $urlImagem = $imagemHome($noticia->imagem ?? ''); ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <article class="card h-100 shadow-sm home-noticia-card">
                                    <div class="home-noticia-media">
                                        <?php if ($urlImagem !== ''): ?>
                                            <img src="<?= $esc($urlImagem) ?>" class="home-noticia-imagem" alt="<?= $esc($noticia->titulo ?? '') ?>" loading="lazy" onerror="this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none');">
                                        <?php endif; ?>
                                        <div class="home-noticia-placeholder <?= $urlImagem !== '' ? 'd-none' : '' ?>">
                                            <i class="bi bi-image fs-1"></i>
                                            <span class="small">Imagem indisponível</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-primary">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            Publicado em: <?= $formatarDataHome($noticia->data_pub ?? null) ?>
                                        </small>
                                        <h5 class="card-title mt-2 fw-semibold"><?= $esc($noticia->titulo ?? '') ?></h5>
                                        <p class="card-text text-body-secondary"><?= $resumirNoticia($noticia->descricao ?? '', 120) ?></p>
                                    </div>
                                    <div class="card-footer bg-white border-0 pb-3">
                                        <a href="/noticias?id=<?= (int)($noticia->id ?? 0) ?>" class="btn btn-primary btn-sm">
                                            Leia Mais <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPaginas > 1): ?>
                        <nav class="mt-4" aria-label="Paginação de notícias">
                            <ul class="pagination flex-wrap justify-content-center gap-1">
                                <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                                    <?php if ($pagina > 1): ?>
                                        <a class="page-link" href="?pagina=<?= $pagina - 1 ?>" aria-label="Página anterior">&laquo;</a>
                                    <?php else: ?>
                                        <span class="page-link" aria-disabled="true">&laquo;</span>
                                    <?php endif; ?>
                                </li>

                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $i ?>" <?= $i === $pagina ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                                    <?php if ($pagina < $totalPaginas): ?>
                                        <a class="page-link" href="?pagina=<?= $pagina + 1 ?>" aria-label="Próxima página">&raquo;</a>
                                    <?php else: ?>
                                        <span class="page-link" aria-disabled="true">&raquo;</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>

            </div>

            <aside class="col-12 col-lg-3 <?= $semNoticias ? 'order-1 order-lg-2' : '' ?>">

                <div class="card shadow-sm mb-4 home-sidebar-card" id="eventos">
                    <div class="card-header">
                        <div><i class="bi bi-calendar-event me-2"></i>Eventos Escolares</div>
                        <?php if (count($eventos) > 3): ?>
                            <span class="sidebar-quantidade"><?= count($eventos) ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($eventos)): ?>
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-x fs-2 text-secondary d-block mb-2"></i>
                            <p class="text-body-secondary small mb-0">Nenhum evento cadastrado no momento.</p>
                        </div>
                    <?php else: ?>
                        <div id="carouselEventos" class="carousel slide" data-bs-interval="false" data-bs-touch="true">
                            <div class="carousel-inner">
                                <?php foreach ($gruposEventos as $indice => $grupo): ?>
                                    <div class="carousel-item <?= $indice === 0 ? 'active' : '' ?>">
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($grupo as $evento): ?>
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-start gap-2">
                                                        <i class="bi bi-calendar-check icone-sidebar"></i>
                                                        <div class="flex-grow-1" style="min-width:0">
                                                            <div class="titulo-sidebar mb-1"><?= $esc($evento->titulo ?? '') ?></div>
                                                            <div class="data-sidebar">
                                                                <i class="bi bi-clock me-1"></i>
                                                                <?= $formatarDataHome($evento->data_evento ?? null) ?>
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
                                <button type="button" class="sidebar-controle sidebar-controle-anterior" data-bs-target="#carouselEventos" data-bs-slide="prev" aria-label="Eventos anteriores">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button type="button" class="sidebar-controle sidebar-controle-proximo" data-bs-target="#carouselEventos" data-bs-slide="next" aria-label="Próximos eventos">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card shadow-sm home-sidebar-card" id="avisos">
                    <div class="card-header">
                        <div><i class="bi bi-megaphone me-2"></i>Avisos</div>
                        <?php if (count($avisos) > 3): ?>
                            <span class="sidebar-quantidade"><?= count($avisos) ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($avisos)): ?>
                        <div class="card-body text-center py-4">
                            <i class="bi bi-bell-slash fs-2 text-secondary d-block mb-2"></i>
                            <p class="text-body-secondary small mb-0">Nenhum aviso disponível no momento.</p>
                        </div>
                    <?php else: ?>
                        <div id="carouselAvisos" class="carousel slide" data-bs-interval="false" data-bs-touch="true">
                            <div class="carousel-inner">
                                <?php foreach ($gruposAvisos as $indice => $grupo): ?>
                                    <div class="carousel-item <?= $indice === 0 ? 'active' : '' ?>">
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($grupo as $aviso): ?>
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-start gap-2">
                                                        <i class="bi bi-info-circle-fill icone-sidebar"></i>
                                                        <div class="flex-grow-1" style="min-width:0">
                                                            <div class="titulo-sidebar"><?= $esc($aviso->titulo ?? '') ?></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (count($gruposAvisos) > 1): ?>
                                <button type="button" class="sidebar-controle sidebar-controle-anterior" data-bs-target="#carouselAvisos" data-bs-slide="prev" aria-label="Avisos anteriores">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button type="button" class="sidebar-controle sidebar-controle-proximo" data-bs-target="#carouselAvisos" data-bs-slide="next" aria-label="Próximos avisos">
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