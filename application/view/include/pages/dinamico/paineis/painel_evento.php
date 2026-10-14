<?php
$eventos = $model["eventos"] ?? [];

if (!is_iterable($eventos)) {
    $eventos = [];
}

$exibirValor = static function ($valor, string $padrao = "-"): string {
    $valor = trim((string)($valor ?? ""));
    return htmlspecialchars(
        $valor !== "" ? $valor : $padrao,
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
};

$formatarData = static function ($data): string {
    if (empty($data)) return "Não definida";

    try {
        return (new DateTimeImmutable((string)$data))->format("d/m/Y");
    } catch (Exception $e) {
        return "Não definida";
    }
};

$listaEventos = [];

foreach ($eventos as $evento) {
    if (!is_object($evento) && !is_array($evento)) continue;

    $evento = (object)$evento;
    $titulo = trim((string)($evento->titulo ?? ""));

    $listaEventos[] = [
        "id" => (int)($evento->id ?? 0),
        "titulo" => $titulo !== "" ? $titulo : "Título não informado",
        "data" => $formatarData($evento->data_evento ?? null),
        "data_original" => (string)($evento->data_evento ?? "")
    ];
}

$quantidadeEventos = count($listaEventos);
?>

<main class="container-fluid py-3 py-md-4 painel-eventos">
    <style>
        .painel-eventos .painel-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .painel-eventos .painel-cabecalho h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .painel-eventos .painel-acoes {
            display: flex;
            gap: .6rem;
            flex-shrink: 0;
        }

        .painel-eventos .painel-card {
            border: 0;
            border-radius: .8rem;
            overflow: hidden;
        }

        .painel-eventos .painel-card-header {
            background: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .painel-eventos .painel-card-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .painel-eventos .quantidade-registros {
            background: rgba(255, 255, 255, .2);
            padding: .2rem .55rem;
            border-radius: 2rem;
            font-size: .75rem;
            white-space: nowrap;
        }

        .painel-eventos .barra-busca {
            background: #fff;
            padding: .85rem 1rem;
            border-bottom: 1px solid #edf0f4;
        }

        .painel-eventos .barra-busca .input-group {
            max-width: 430px;
        }

        .painel-eventos .barra-busca .form-control,
        .painel-eventos .barra-busca .input-group-text {
            border-color: #dce3ed;
        }

        .painel-eventos .tabela-scroll {
            max-height: 400px;
            overflow: auto;
        }

        .painel-eventos .tabela-gerenciamento {
            margin-bottom: 0;
            min-width: 680px;
        }

        .painel-eventos .tabela-gerenciamento thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #e9f2ff;
            color: #212529;
            font-weight: 600;
            border-bottom: 2px solid #0d6efd;
            white-space: nowrap;
            vertical-align: middle;
            height: 48px;
        }

        .painel-eventos .tabela-gerenciamento th:not(:last-child),
        .painel-eventos .tabela-gerenciamento td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .painel-eventos .tabela-gerenciamento thead th:not(:last-child) {
            border-right-color: #c7d7ee;
        }

        .painel-eventos .tabela-gerenciamento tbody tr {
            height: 58px;
        }

        .painel-eventos .tabela-gerenciamento tbody td {
            background: #fff;
            vertical-align: middle;
        }

        .painel-eventos .tabela-gerenciamento tbody tr:hover td {
            background: #f8fbff;
        }

        .painel-eventos .titulo-evento-desktop {
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .painel-eventos .acoes-tabela {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            white-space: nowrap;
        }

        .painel-eventos .acao-tabela {
            border-radius: .4rem;
            transition: transform .18s ease;
        }

        .painel-eventos .acao-tabela:hover {
            transform: translateY(-1px);
        }

        .painel-eventos .cards-mobile {
            display: none;
            background: #f8fafc;
        }

        .painel-eventos .cards-mobile-lista {
            display: grid;
            grid-template-columns: 1fr;
            gap: .75rem;
            padding: .85rem;
        }

        .painel-eventos .card-evento-mobile {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .painel-eventos .evento-mobile-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            margin-bottom: .75rem;
        }

        .painel-eventos .evento-mobile-identificacao {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: #64748b;
            font-size: .8rem;
            font-weight: 600;
        }

        .painel-eventos .evento-mobile-icone {
            width: 30px;
            height: 30px;
            min-width: 30px;
            border-radius: 8px;
            background: #eaf2ff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .painel-eventos .evento-mobile-data {
            font-size: .76rem;
            font-weight: 600;
            color: #0d6efd;
            background: #eaf2ff;
            padding: .3rem .55rem;
            border-radius: 6px;
            white-space: nowrap;
        }

        .painel-eventos .titulo-evento-mobile {
            font-size: .96rem;
            line-height: 1.5;
            font-weight: 700;
            color: #263345;
            overflow-wrap: anywhere;
            margin-bottom: .85rem;
        }

        .painel-eventos .evento-mobile-info {
            display: flex;
            align-items: center;
            gap: .55rem;
            color: #64748b;
            font-size: .83rem;
            margin-bottom: .9rem;
        }

        .painel-eventos .evento-mobile-info i {
            color: #0d6efd;
        }

        .painel-eventos .acoes-mobile {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .55rem;
            border-top: 1px solid #edf0f4;
            padding-top: .85rem;
            margin-top: auto;
        }

        .painel-eventos .acoes-mobile .btn {
            width: 100%;
            min-height: 39px;
            border-radius: 7px;
            font-size: .85rem;
            font-weight: 600;
        }

        .painel-eventos .mobile-lista-rodape {
            padding: .4rem .85rem 1rem;
            text-align: center;
        }

        .painel-eventos .mobile-lista-contador {
            font-size: .78rem;
            color: #6c757d;
            margin-bottom: .65rem;
        }

        .painel-eventos .mobile-mostrar-mais {
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            padding: .55rem 1.25rem;
        }

        .painel-eventos .estado-vazio {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .painel-eventos .estado-vazio i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .painel-eventos .sem-resultados {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #6c757d;
        }

        .painel-eventos [hidden] {
            display: none !important;
        }

        .painel-eventos .tabela-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .painel-eventos .tabela-scroll::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }

        .painel-eventos .tabela-scroll::-webkit-scrollbar-track {
            background: #f1f3f5;
        }

        @media (min-width:700px) and (max-width:991.98px) {
            .painel-eventos .cards-mobile-lista {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width:991.98px) {
            .painel-eventos .tabela-desktop {
                display: none;
            }

            .painel-eventos .cards-mobile {
                display: block;
            }
        }

        @media (max-width:767.98px) {
            .painel-eventos .painel-cabecalho {
                flex-direction: column;
                align-items: stretch;
            }

            .painel-eventos .painel-cabecalho h1 {
                font-size: 1.35rem;
            }

            .painel-eventos .painel-acoes {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .painel-eventos .painel-acoes .btn {
                width: 100%;
            }

            .painel-eventos .barra-busca .input-group {
                max-width: none;
            }
        }

        @media (max-width:575.98px) {
            .painel-eventos .painel-acoes {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="painel-cabecalho">
                <div>
                    <h1>
                        <i class="bi bi-calendar-event-fill text-primary me-2"></i>
                        Eventos
                    </h1>
                    <div class="text-body-secondary mt-1">
                        Gerencie os eventos cadastrados no portal.
                    </div>
                </div>

                <div class="painel-acoes">
                    <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>

                    <a href="/cadastro/evento" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Novo Evento
                    </a>
                </div>
            </div>

            <section class="card shadow-sm painel-card" data-painel-eventos>

                <div class="painel-card-header">
                    <h2>
                        <i class="bi bi-calendar-event me-2"></i>
                        Eventos cadastrados
                    </h2>

                    <span class="quantidade-registros">
                        <?= $quantidadeEventos ?>
                        <?= $quantidadeEventos === 1 ? "registro" : "registros" ?>
                    </span>
                </div>

                <?php if ($quantidadeEventos === 0): ?>

                    <div class="estado-vazio">
                        <i class="bi bi-calendar-x"></i>
                        <strong>Nenhum evento cadastrado</strong>
                        <div class="small mt-1">
                            Cadastre um evento para começar a gerenciar as atividades.
                        </div>
                    </div>

                <?php else: ?>

                    <div class="barra-busca">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-secondary"></i>
                            </span>

                            <input
                                type="search"
                                class="form-control"
                                placeholder="Pesquisar por ID, título ou data..."
                                aria-label="Pesquisar eventos"
                                autocomplete="off"
                                data-busca-eventos>
                        </div>
                    </div>

                    <div class="tabela-scroll tabela-desktop">
                        <table class="table table-hover tabela-gerenciamento">
                            <thead>
                                <tr>
                                    <th class="ps-3 ps-md-4">ID</th>
                                    <th>Título</th>
                                    <th>Data do Evento</th>
                                    <th class="text-center pe-3 pe-md-4">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($listaEventos as $evento): ?>
                                    <?php
                                    $busca = $evento["id"] . " "
                                        . $evento["titulo"] . " "
                                        . $evento["data"] . " "
                                        . $evento["data_original"];
                                    ?>

                                    <tr
                                        data-linha-evento
                                        data-busca="<?= $exibirValor($busca) ?>">

                                        <td class="ps-3 ps-md-4">
                                            <?= $evento["id"] ?>
                                        </td>

                                        <td style="min-width:180px">
                                            <div class="titulo-evento-desktop">
                                                <i class="bi bi-calendar-check text-primary me-1"></i>
                                                <?= $exibirValor($evento["titulo"]) ?>
                                            </div>
                                        </td>

                                        <td class="text-nowrap">
                                            <i class="bi bi-calendar3 text-primary me-1"></i>
                                            <?= $exibirValor($evento["data"]) ?>
                                        </td>

                                        <td class="pe-3 pe-md-4">
                                            <div class="acoes-tabela">
                                                <a
                                                    href="/atualizar/evento?id=<?= $evento["id"] ?>"
                                                    class="btn btn-warning btn-sm acao-tabela"
                                                    title="Editar evento">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Editar
                                                </a>

                                                <a
                                                    href="/excluir/evento?id=<?= $evento["id"] ?>"
                                                    class="btn btn-danger btn-sm acao-tabela"
                                                    title="Excluir evento"
                                                    data-confirmar-exclusao
                                                    data-titulo="<?= $exibirValor($evento["titulo"]) ?>">
                                                    <i class="bi bi-trash-fill me-1"></i>
                                                    Excluir
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cards-mobile">
                        <div class="cards-mobile-lista">

                            <?php foreach ($listaEventos as $evento): ?>
                                <?php
                                $busca = $evento["id"] . " "
                                    . $evento["titulo"] . " "
                                    . $evento["data"] . " "
                                    . $evento["data_original"];
                                ?>

                                <article
                                    class="card-evento-mobile"
                                    data-card-evento
                                    data-busca="<?= $exibirValor($busca) ?>">

                                    <div class="evento-mobile-topo">
                                        <div class="evento-mobile-identificacao">
                                            <div class="evento-mobile-icone">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>
                                            Evento #<?= $evento["id"] ?>
                                        </div>

                                        <span class="evento-mobile-data">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?= $exibirValor($evento["data"]) ?>
                                        </span>
                                    </div>

                                    <h3 class="titulo-evento-mobile">
                                        <?= $exibirValor($evento["titulo"]) ?>
                                    </h3>

                                    <div class="acoes-mobile">
                                        <a
                                            href="/atualizar/evento?id=<?= $evento["id"] ?>"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Editar
                                        </a>

                                        <a
                                            href="/excluir/evento?id=<?= $evento["id"] ?>"
                                            class="btn btn-danger"
                                            data-confirmar-exclusao
                                            data-titulo="<?= $exibirValor($evento["titulo"]) ?>">
                                            <i class="bi bi-trash-fill me-1"></i>
                                            Excluir
                                        </a>
                                    </div>
                                </article>

                            <?php endforeach; ?>

                        </div>

                        <div class="mobile-lista-rodape" data-rodape-mobile>
                            <div class="mobile-lista-contador" data-contador-mobile></div>

                            <button
                                type="button"
                                class="btn btn-outline-primary mobile-mostrar-mais"
                                data-mostrar-mais
                                hidden>
                                <i class="bi bi-chevron-down me-1"></i>
                                <span data-texto-mais>Mostrar mais 5</span>
                            </button>
                        </div>
                    </div>

                    <div class="sem-resultados" data-sem-resultados hidden>
                        <i class="bi bi-search fs-2 d-block mb-2"></i>
                        Nenhum evento corresponde à pesquisa.
                    </div>

                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const painel = document.querySelector(".painel-eventos [data-painel-eventos]");
        if (!painel) return;

        const normalizar = texto => String(texto || "")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim();

        const busca = painel.querySelector("[data-busca-eventos]");
        const linhas = [...painel.querySelectorAll("[data-linha-evento]")];
        const cards = [...painel.querySelectorAll("[data-card-evento]")];
        const botaoMais = painel.querySelector("[data-mostrar-mais]");
        const textoMais = painel.querySelector("[data-texto-mais]");
        const contador = painel.querySelector("[data-contador-mobile]");
        const rodape = painel.querySelector("[data-rodape-mobile]");
        const semResultados = painel.querySelector("[data-sem-resultados]");

        let limiteMobile = 5;

        function atualizarLista() {
            if (!busca) return;

            const termo = normalizar(busca.value);
            let encontrados = 0;
            let exibidos = 0;

            linhas.forEach(linha => {
                const corresponde = normalizar(linha.dataset.busca).includes(termo);
                linha.hidden = !corresponde;
            });

            cards.forEach(card => {
                const corresponde = normalizar(card.dataset.busca).includes(termo);

                if (!corresponde) {
                    card.hidden = true;
                    return;
                }

                encontrados++;

                if (exibidos < limiteMobile) {
                    card.hidden = false;
                    exibidos++;
                } else {
                    card.hidden = true;
                }
            });

            if (semResultados) {
                semResultados.hidden = encontrados !== 0;
            }

            if (rodape) {
                rodape.hidden = encontrados === 0;
            }

            if (contador) {
                contador.textContent =
                    "Exibindo " + exibidos + " de " + encontrados + " eventos";
            }

            if (botaoMais) {
                const restantes = Math.max(0, encontrados - limiteMobile);

                botaoMais.hidden = restantes === 0;

                if (textoMais) {
                    textoMais.textContent =
                        "Mostrar mais " + Math.min(5, restantes);
                }
            }
        }

        if (busca) {
            busca.addEventListener("input", () => {
                limiteMobile = 5;
                atualizarLista();
            });
        }

        if (botaoMais) {
            botaoMais.addEventListener("click", () => {
                limiteMobile += 5;
                atualizarLista();
            });
        }

        painel.querySelectorAll("[data-confirmar-exclusao]").forEach(link => {
            link.addEventListener("click", event => {
                if (link.dataset.enviando === "1") {
                    event.preventDefault();
                    return;
                }

                const titulo = link.dataset.titulo || "este evento";

                const confirmar = confirm(
                    'Deseja realmente excluir o evento "' +
                    titulo +
                    '"?\n\nEssa ação não poderá ser desfeita.'
                );

                if (!confirmar) {
                    event.preventDefault();
                    return;
                }

                link.dataset.enviando = "1";
            });
        });

        atualizarLista();
    });
</script>