<?php
$avisos = $model["avisos"] ?? [];

if (!is_iterable($avisos)) {
    $avisos = [];
}

$exibirValor = static function ($valor, string $padrao = "-"): string {
    $valor = trim((string)($valor ?? ""));
    return htmlspecialchars(
        $valor !== "" ? $valor : $padrao,
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
};

$listaAvisos = [];

foreach ($avisos as $aviso) {
    if (!is_object($aviso) && !is_array($aviso)) {
        continue;
    }

    $aviso = (object)$aviso;

    $id = (int)($aviso->id ?? 0);
    $titulo = trim((string)($aviso->titulo ?? ""));
    $ativo = $aviso->ativo ?? null;

    if ($ativo !== null && (string)$ativo !== "") {
        if ((int)$ativo === 1) {
            $statusTexto = "Ativo";
            $statusClasse = "success";
            $statusIcone = "bi-check-circle";
        } elseif ((int)$ativo === 0) {
            $statusTexto = "Inativo";
            $statusClasse = "danger";
            $statusIcone = "bi-x-circle";
        } else {
            $statusTexto = "Indefinido";
            $statusClasse = "secondary";
            $statusIcone = "bi-question-circle";
        }
    } else {
        $statusTexto = "Indefinido";
        $statusClasse = "secondary";
        $statusIcone = "bi-question-circle";
    }

    $listaAvisos[] = [
        "id" => $id,
        "titulo" => $titulo !== "" ? $titulo : "Título não informado",
        "status" => $statusTexto,
        "classe" => $statusClasse,
        "icone" => $statusIcone
    ];
}

$quantidadeAvisos = count($listaAvisos);
?>

<main class="container-fluid py-3 py-md-4 painel-avisos">

    <style>
        .painel-avisos .painel-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .painel-avisos .painel-cabecalho h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .painel-avisos .painel-acoes {
            display: flex;
            gap: .6rem;
            flex-shrink: 0;
        }

        .painel-avisos .painel-card {
            border: 0;
            border-radius: .8rem;
            overflow: hidden;
        }

        .painel-avisos .painel-card-header {
            background: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .painel-avisos .painel-card-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .painel-avisos .quantidade-registros {
            background: rgba(255, 255, 255, .2);
            padding: .2rem .55rem;
            border-radius: 2rem;
            font-size: .75rem;
            white-space: nowrap;
        }

        .painel-avisos .barra-busca {
            background: #fff;
            padding: .85rem 1rem;
            border-bottom: 1px solid #edf0f4;
        }

        .painel-avisos .barra-busca .input-group {
            max-width: 430px;
        }

        .painel-avisos .barra-busca .form-control,
        .painel-avisos .barra-busca .input-group-text {
            border-color: #dce3ed;
        }

        .painel-avisos .tabela-scroll {
            max-height: 400px;
            overflow: auto;
        }

        .painel-avisos .tabela-gerenciamento {
            margin-bottom: 0;
            min-width: 650px;
        }

        .painel-avisos .tabela-gerenciamento thead th {
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

        .painel-avisos .tabela-gerenciamento th:not(:last-child),
        .painel-avisos .tabela-gerenciamento td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .painel-avisos .tabela-gerenciamento thead th:not(:last-child) {
            border-right-color: #c7d7ee;
        }

        .painel-avisos .tabela-gerenciamento tbody tr {
            height: 58px;
        }

        .painel-avisos .tabela-gerenciamento tbody td {
            background: #fff;
            vertical-align: middle;
        }

        .painel-avisos .tabela-gerenciamento tbody tr:hover td {
            background: #f8fbff;
        }

        .painel-avisos .titulo-aviso-desktop {
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .painel-avisos .status-aviso {
            display: inline-block;
            font-size: .75rem;
            font-weight: 600;
            min-width: 85px;
            padding: .45rem .6rem;
        }

        .painel-avisos .acoes-tabela {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            white-space: nowrap;
        }

        .painel-avisos .acao-tabela {
            border-radius: .4rem;
            transition: transform .18s;
        }

        .painel-avisos .acao-tabela:hover {
            transform: translateY(-1px);
        }

        .painel-avisos .cards-mobile {
            display: none;
            background: #f8fafc;
        }

        .painel-avisos .cards-mobile-lista {
            display: grid;
            grid-template-columns: 1fr;
            gap: .75rem;
            padding: .85rem;
        }

        .painel-avisos .card-aviso-mobile {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
            min-width: 0;
        }

        .painel-avisos .aviso-mobile-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            margin-bottom: .75rem;
        }

        .painel-avisos .aviso-mobile-identificacao {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: #64748b;
            font-size: .8rem;
            font-weight: 600;
        }

        .painel-avisos .aviso-mobile-icone {
            width: 29px;
            height: 29px;
            border-radius: 8px;
            background: #eaf2ff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .painel-avisos .titulo-aviso-mobile {
            font-size: .96rem;
            line-height: 1.5;
            font-weight: 700;
            color: #263345;
            overflow-wrap: anywhere;
            margin-bottom: .9rem;
        }

        .painel-avisos .acoes-mobile {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .55rem;
            border-top: 1px solid #edf0f4;
            padding-top: .85rem;
        }

        .painel-avisos .acoes-mobile .btn {
            width: 100%;
            min-height: 39px;
            border-radius: 7px;
            font-size: .85rem;
            font-weight: 600;
        }

        .painel-avisos .mobile-lista-rodape {
            padding: .4rem .85rem 1rem;
            text-align: center;
        }

        .painel-avisos .mobile-lista-contador {
            font-size: .78rem;
            color: #6c757d;
            margin-bottom: .65rem;
        }

        .painel-avisos .mobile-mostrar-mais {
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            padding: .55rem 1.25rem;
        }

        .painel-avisos .estado-vazio {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .painel-avisos .estado-vazio i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .painel-avisos .sem-resultados {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .painel-avisos [hidden] {
            display: none !important;
        }

        .painel-avisos .tabela-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .painel-avisos .tabela-scroll::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }

        .painel-avisos .tabela-scroll::-webkit-scrollbar-track {
            background: #f1f3f5;
        }

        @media(min-width:700px) and (max-width:991.98px) {
            .painel-avisos .cards-mobile-lista {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width:991.98px) {
            .painel-avisos .tabela-desktop {
                display: none;
            }

            .painel-avisos .cards-mobile {
                display: block;
            }
        }

        @media(max-width:767.98px) {
            .painel-avisos .painel-cabecalho {
                flex-direction: column;
                align-items: stretch;
            }

            .painel-avisos .painel-cabecalho h1 {
                font-size: 1.35rem;
            }

            .painel-avisos .painel-acoes {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .painel-avisos .painel-acoes .btn {
                width: 100%;
            }

            .painel-avisos .barra-busca .input-group {
                max-width: none;
            }
        }

        @media(max-width:575.98px) {
            .painel-avisos .painel-acoes {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">

            <div class="painel-cabecalho">
                <div>
                    <h1>
                        <i class="bi bi-megaphone-fill text-primary me-2"></i>
                        Avisos
                    </h1>
                    <div class="text-body-secondary mt-1">
                        Gerencie os avisos publicados no portal.
                    </div>
                </div>

                <div class="painel-acoes">
                    <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>

                    <a href="/cadastro/aviso" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Novo Aviso
                    </a>
                </div>
            </div>

            <section class="card shadow-sm painel-card" data-painel-avisos>

                <div class="painel-card-header">
                    <h2>
                        <i class="bi bi-megaphone me-2"></i>
                        Avisos cadastrados
                    </h2>

                    <span class="quantidade-registros">
                        <?= $quantidadeAvisos ?>
                        <?= $quantidadeAvisos === 1 ? "registro" : "registros" ?>
                    </span>
                </div>

                <?php if ($quantidadeAvisos === 0): ?>

                    <div class="estado-vazio">
                        <i class="bi bi-megaphone"></i>
                        <strong>Nenhum aviso cadastrado</strong>
                        <div class="small mt-1">
                            Cadastre um aviso para começar a gerenciar os comunicados.
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
                                placeholder="Pesquisar por ID, título ou status..."
                                aria-label="Pesquisar avisos"
                                autocomplete="off"
                                data-busca-avisos>
                        </div>
                    </div>

                    <div class="tabela-scroll tabela-desktop">
                        <table class="table table-hover tabela-gerenciamento">
                            <thead>
                                <tr>
                                    <th class="ps-3 ps-md-4">ID</th>
                                    <th>Título</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center pe-3 pe-md-4">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($listaAvisos as $aviso): ?>
                                    <?php
                                    $busca = $aviso["id"] . " " . $aviso["titulo"] . " " . $aviso["status"];
                                    ?>

                                    <tr
                                        data-linha-aviso
                                        data-busca="<?= $exibirValor($busca) ?>">

                                        <td class="ps-3 ps-md-4">
                                            <?= $aviso["id"] ?>
                                        </td>

                                        <td style="min-width:180px">
                                            <div class="titulo-aviso-desktop">
                                                <i class="bi bi-megaphone text-primary me-1"></i>
                                                <?= $exibirValor($aviso["titulo"]) ?>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge text-bg-<?= $aviso["classe"] ?> status-aviso">
                                                <i class="bi <?= $aviso["icone"] ?> me-1"></i>
                                                <?= $aviso["status"] ?>
                                            </span>
                                        </td>

                                        <td class="pe-3 pe-md-4">
                                            <div class="acoes-tabela">
                                                <a
                                                    href="/atualizar/aviso?id=<?= $aviso["id"] ?>"
                                                    class="btn btn-warning btn-sm acao-tabela"
                                                    title="Editar aviso">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Editar
                                                </a>

                                                <a
                                                    href="/excluir/aviso?id=<?= $aviso["id"] ?>"
                                                    class="btn btn-danger btn-sm acao-tabela"
                                                    title="Excluir aviso"
                                                    data-confirmar-exclusao
                                                    data-titulo="<?= $exibirValor($aviso["titulo"]) ?>">
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

                            <?php foreach ($listaAvisos as $aviso): ?>
                                <?php
                                $busca = $aviso["id"] . " " . $aviso["titulo"] . " " . $aviso["status"];
                                ?>

                                <article
                                    class="card-aviso-mobile"
                                    data-card-aviso
                                    data-busca="<?= $exibirValor($busca) ?>">

                                    <div class="aviso-mobile-topo">
                                        <div class="aviso-mobile-identificacao">
                                            <div class="aviso-mobile-icone">
                                                <i class="bi bi-megaphone"></i>
                                            </div>
                                            Aviso #<?= $aviso["id"] ?>
                                        </div>

                                        <span class="badge text-bg-<?= $aviso["classe"] ?> status-aviso">
                                            <?= $aviso["status"] ?>
                                        </span>
                                    </div>

                                    <h3 class="titulo-aviso-mobile">
                                        <?= $exibirValor($aviso["titulo"]) ?>
                                    </h3>

                                    <div class="acoes-mobile">
                                        <a
                                            href="/atualizar/aviso?id=<?= $aviso["id"] ?>"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Editar
                                        </a>

                                        <a
                                            href="/excluir/aviso?id=<?= $aviso["id"] ?>"
                                            class="btn btn-danger"
                                            data-confirmar-exclusao
                                            data-titulo="<?= $exibirValor($aviso["titulo"]) ?>">
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
                        Nenhum aviso corresponde à pesquisa.
                    </div>

                <?php endif; ?>

            </section>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const painel = document.querySelector(".painel-avisos [data-painel-avisos]");
        if (!painel) return;

        const normalizar = texto => String(texto || "")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim();

        const busca = painel.querySelector("[data-busca-avisos]");
        const linhas = [...painel.querySelectorAll("[data-linha-aviso]")];
        const cards = [...painel.querySelectorAll("[data-card-aviso]")];
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
                contador.textContent = "Exibindo " + exibidos + " de " + encontrados + " avisos";
            }

            if (botaoMais) {
                const restantes = Math.max(0, encontrados - limiteMobile);
                botaoMais.hidden = restantes === 0;

                if (textoMais) {
                    textoMais.textContent = "Mostrar mais " + Math.min(5, restantes);
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
                const titulo = link.dataset.titulo || "este aviso";

                const confirmar = confirm(
                    'Deseja realmente excluir o aviso "' + titulo + '"?\n\nEssa ação não poderá ser desfeita.'
                );

                if (!confirmar) {
                    event.preventDefault();
                    return;
                }

                if (link.dataset.enviando === "1") {
                    event.preventDefault();
                    return;
                }

                link.dataset.enviando = "1";
            });
        });

        atualizarLista();
    });
</script>