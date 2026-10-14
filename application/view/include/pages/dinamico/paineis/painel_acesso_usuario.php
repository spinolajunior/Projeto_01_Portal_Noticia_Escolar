<?php
$nomesSeries = [
    1 => "1º Ano Integral",
    2 => "1º Ano Técnico",
    3 => "2º Ano Integral",
    4 => "2º Ano Técnico",
    5 => "3º Ano Integral",
    6 => "3º Ano Técnico",
    9 => "EJA"
];

$administradores = $model["adm"] ?? [];
$alunos = $model["alunos"] ?? [];

$administradores = is_iterable($administradores) ? $administradores : [];
$alunos = is_iterable($alunos) ? $alunos : [];

$exibirValor = static function ($valor, string $padrao = "-"): string {
    $valor = trim((string)($valor ?? ""));
    return htmlspecialchars($valor !== "" ? $valor : $padrao, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
};

$prepararUsuarios = static function ($lista, string $tipo) use ($nomesSeries): array {
    $resultado = [];

    foreach ($lista as $registro) {
        if (!is_object($registro) && !is_array($registro)) continue;

        $registro = (object)$registro;
        $nome = trim((string)($registro->nome ?? ""));
        $matricula = trim((string)($registro->matricula ?? ""));

        if ($tipo === "administrador") {
            $detalhe = $registro->cargo
                ?? $registro->nome_tipo
                ?? $registro->tipo
                ?? "";
        } else {
            $serie = trim((string)($registro->nome_serie ?? ""));

            if ($serie === "") {
                $serie = trim((string)($registro->serie ?? ""));
            }

            if ($serie === "") {
                $serie = trim((string)($registro->id_serie ?? ""));
            }

            if ($serie !== "" && ctype_digit($serie)) {
                $idSerie = (int)$serie;
                $detalhe = $nomesSeries[$idSerie] ?? "Série ID " . $idSerie;
            } else {
                $detalhe = $serie;
            }
        }

        $idCredenciais = (int)($registro->id_credenciais ?? 0);
        $valorAtivo = $registro->ativo ?? null;
        $ativo = ($valorAtivo === null || $valorAtivo === "")
            ? null
            : (int)$valorAtivo;

        if ($ativo !== null && !in_array($ativo, [0, 1], true)) {
            $ativo = null;
        }

        $resultado[] = [
            "nome" => $nome,
            "matricula" => $matricula,
            "detalhe" => trim((string)$detalhe),
            "id_credenciais" => $idCredenciais,
            "ativo" => $ativo,
            "disponivel" => $idCredenciais > 0 && $ativo !== null
        ];
    }

    return $resultado;
};

$administradores = $prepararUsuarios($administradores, "administrador");
$alunos = $prepararUsuarios($alunos, "aluno");

$exibirAdministradores = (int)($_SESSION["acesso"] ?? 0) > 1;

$secoes = [];

if ($exibirAdministradores) {
    $secoes[] = [
        "id" => "administradores",
        "titulo" => "Administradores",
        "icone" => "bi-person-badge-fill",
        "vazio" => "Nenhum administrador encontrado.",
        "usuarios" => $administradores,
        "coluna" => "Cargo"
    ];
}

$secoes[] = [
    "id" => "alunos",
    "titulo" => "Alunos",
    "icone" => "bi-mortarboard-fill",
    "vazio" => "Nenhum aluno encontrado.",
    "usuarios" => $alunos,
    "coluna" => "Série"
];
?>

<main class="container-fluid py-3 py-md-4 painel-acesso">

    <style>
        .painel-acesso .painel-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .painel-acesso .painel-cabecalho h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .painel-acesso .card-acesso {
            border: 0;
            border-radius: .8rem;
            overflow: hidden;
        }

        .painel-acesso .card-acesso-header {
            background: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .painel-acesso .card-acesso-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .painel-acesso .quantidade-registros {
            background: rgba(255, 255, 255, .2);
            padding: .2rem .55rem;
            border-radius: 2rem;
            font-size: .75rem;
            white-space: nowrap;
        }

        .painel-acesso .barra-busca {
            padding: .85rem 1rem;
            background: #fff;
            border-bottom: 1px solid #edf0f4;
        }

        .painel-acesso .barra-busca .input-group {
            max-width: 430px;
        }

        .painel-acesso .barra-busca .form-control,
        .painel-acesso .barra-busca .input-group-text {
            border-color: #dce3ed;
        }

        .painel-acesso .tabela-scroll {
            max-height: 350px;
            overflow: auto;
        }

        .painel-acesso .tabela-acesso {
            margin-bottom: 0;
            min-width: 650px;
        }

        .painel-acesso .tabela-acesso thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            height: 48px;
            background: #e9f2ff;
            color: #212529;
            border-bottom: 2px solid #0d6efd;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
        }

        .painel-acesso .tabela-acesso th:not(:last-child),
        .painel-acesso .tabela-acesso td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .painel-acesso .tabela-acesso tbody tr {
            height: 58px;
        }

        .painel-acesso .tabela-acesso tbody td {
            background: #fff;
            vertical-align: middle;
        }

        .painel-acesso .tabela-acesso tbody tr:hover td {
            background: #f8fbff;
        }

        .painel-acesso .nome-usuario {
            font-weight: 600;
            color: #212529;
            overflow-wrap: anywhere;
        }

        .painel-acesso .acesso-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .65rem;
        }

        .painel-acesso .acesso-switch {
            padding-left: 0;
            margin: 0;
        }

        .painel-acesso .acesso-switch .form-check-input {
            float: none;
            margin: 0;
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
            border-color: #adb5bd;
        }

        .painel-acesso .acesso-switch .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .painel-acesso .acesso-switch .form-check-input:not(:checked) {
            background-color: #dee2e6;
        }

        .painel-acesso .acesso-switch .form-check-input:focus {
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15);
        }

        .painel-acesso .texto-acesso {
            min-width: 32px;
            font-size: .78rem;
            font-weight: 700;
        }

        .painel-acesso .acesso-on {
            color: #198754;
        }

        .painel-acesso .acesso-off {
            color: #6c757d;
        }

        .painel-acesso .acesso-indisponivel {
            color: #adb5bd;
            font-size: .82rem;
            font-weight: 600;
        }

        .painel-acesso .estado-vazio {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .painel-acesso .estado-vazio i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .painel-acesso .cards-mobile {
            display: none;
            background: #f8fafc;
        }

        .painel-acesso .cards-mobile-lista {
            display: grid;
            gap: .7rem;
            padding: .8rem;
        }

        .painel-acesso .usuario-mobile-card {
            background: #fff;
            border: 1px solid #e3e9f1;
            border-radius: .75rem;
            padding: .95rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .035);
        }

        .painel-acesso .usuario-mobile-topo {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: .7rem;
            min-width: 0;
        }

        .painel-acesso .usuario-mobile-icone {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            background: #eaf2ff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .painel-acesso .usuario-mobile-nome {
            font-size: .93rem;
            font-weight: 700;
            color: #212529;
            overflow-wrap: anywhere;
            min-width: 0;
        }

        .painel-acesso .usuario-mobile-info {
            display: grid;
            gap: .4rem;
            margin-bottom: .8rem;
        }

        .painel-acesso .usuario-mobile-linha {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: .75rem;
            font-size: .82rem;
        }

        .painel-acesso .usuario-mobile-label {
            color: #6c757d;
            flex-shrink: 0;
        }

        .painel-acesso .usuario-mobile-valor {
            color: #273444;
            font-weight: 600;
            text-align: right;
            overflow-wrap: anywhere;
            min-width: 0;
        }

        .painel-acesso .acesso-mobile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            border-top: 1px solid #edf0f4;
            padding-top: .75rem;
        }

        .painel-acesso .acesso-mobile-titulo {
            color: #495057;
            font-size: .83rem;
            font-weight: 600;
        }

        .painel-acesso .mobile-lista-rodape {
            padding: .4rem .85rem 1rem;
            text-align: center;
        }

        .painel-acesso .mobile-lista-contador {
            font-size: .78rem;
            color: #6c757d;
            margin-bottom: .6rem;
        }

        .painel-acesso .mobile-mostrar-mais {
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            padding: .5rem 1.2rem;
        }

        .painel-acesso [hidden] {
            display: none !important;
        }

        .painel-acesso .tabela-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .painel-acesso .tabela-scroll::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }

        @media(max-width:767.98px) {
            .painel-acesso .painel-cabecalho {
                flex-direction: column;
                align-items: stretch;
            }

            .painel-acesso .painel-cabecalho h1 {
                font-size: 1.35rem;
            }

            .painel-acesso .painel-acoes .btn {
                width: 100%;
            }

            .painel-acesso .tabela-desktop {
                display: none;
            }

            .painel-acesso .cards-mobile {
                display: block;
            }

            .painel-acesso .barra-busca .input-group {
                max-width: none;
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">

            <div class="painel-cabecalho">
                <div>
                    <h1>
                        <i class="bi bi-shield-lock-fill me-2 text-primary"></i>
                        Controle de Acesso
                    </h1>
                    <div class="text-body-secondary mt-1">
                        Ative ou desative o acesso dos usuários ao sistema.
                    </div>
                </div>

                <div class="painel-acoes">
                    <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>
            </div>

            <?php foreach ($secoes as $secao): ?>
                <?php
                $usuarios = $secao["usuarios"];
                $quantidade = count($usuarios);
                $idSecao = $secao["id"];
                ?>

                <section class="card shadow-sm card-acesso mb-4" data-painel-usuarios>

                    <div class="card-acesso-header">
                        <h2>
                            <i class="bi <?= $secao["icone"] ?> me-2"></i>
                            <?= $exibirValor($secao["titulo"]) ?>
                        </h2>

                        <span class="quantidade-registros">
                            <?= $quantidade ?>
                            <?= $quantidade === 1 ? "registro" : "registros" ?>
                        </span>
                    </div>

                    <?php if ($quantidade === 0): ?>

                        <div class="estado-vazio">
                            <i class="bi bi-person-x"></i>
                            <strong><?= $exibirValor($secao["vazio"]) ?></strong>
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
                                    placeholder="Pesquisar por nome, matrícula ou série..."
                                    aria-label="Pesquisar <?= $exibirValor($secao["titulo"]) ?>"
                                    autocomplete="off"
                                    data-busca-acesso>
                            </div>
                        </div>

                        <div class="tabela-scroll tabela-desktop">
                            <table class="table table-hover tabela-acesso">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Matrícula</th>
                                        <th><?= $exibirValor($secao["coluna"]) ?></th>
                                        <th class="text-center">Acesso</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <?php
                                        $nome = $usuario["nome"] !== "" ? $usuario["nome"] : "Nome não informado";
                                        $busca = $nome . " " . $usuario["matricula"] . " " . $usuario["detalhe"];
                                        ?>

                                        <tr data-linha-usuario data-busca="<?= $exibirValor($busca) ?>">
                                            <td>
                                                <div class="nome-usuario">
                                                    <i class="bi bi-person-circle text-primary me-1"></i>
                                                    <?= $exibirValor($nome) ?>
                                                </div>
                                            </td>

                                            <td>
                                                <?= $exibirValor($usuario["matricula"], "Não informada") ?>
                                            </td>

                                            <td>
                                                <i class="bi <?= $idSecao === "administradores" ? "bi-person-badge" : "bi-mortarboard" ?> text-primary me-1"></i>
                                                <?= $exibirValor($usuario["detalhe"]) ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if ($usuario["disponivel"]): ?>
                                                    <form
                                                        method="POST"
                                                        action="/painel/acesso/usuario"
                                                        class="d-inline-block"
                                                        data-form-acesso
                                                        data-nome="<?= $exibirValor($nome) ?>">

                                                        <input type="hidden" name="id_credenciais" value="<?= $usuario["id_credenciais"] ?>">
                                                        <input type="hidden" name="ativo" value="<?= $usuario["ativo"] ?>">

                                                        <div class="acesso-container">
                                                            <div class="form-check form-switch acesso-switch">
                                                                <input
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    role="switch"
                                                                    data-alterar-acesso
                                                                    aria-label="Alterar acesso de <?= $exibirValor($nome) ?>"
                                                                    <?= $usuario["ativo"] === 1 ? "checked" : "" ?>>
                                                            </div>

                                                            <span class="texto-acesso <?= $usuario["ativo"] === 1 ? "acesso-on" : "acesso-off" ?>">
                                                                <?= $usuario["ativo"] === 1 ? "ON" : "OFF" ?>
                                                            </span>
                                                        </div>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="acesso-indisponivel">Indisponível</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="cards-mobile">
                            <div class="cards-mobile-lista">

                                <?php foreach ($usuarios as $usuario): ?>
                                    <?php
                                    $nome = $usuario["nome"] !== "" ? $usuario["nome"] : "Nome não informado";
                                    $busca = $nome . " " . $usuario["matricula"] . " " . $usuario["detalhe"];
                                    ?>

                                    <article class="usuario-mobile-card" data-card-usuario data-busca="<?= $exibirValor($busca) ?>">

                                        <div class="usuario-mobile-topo">
                                            <div class="usuario-mobile-icone">
                                                <i class="bi <?= $idSecao === "administradores" ? "bi-person-badge" : "bi-person" ?>"></i>
                                            </div>

                                            <div class="usuario-mobile-nome">
                                                <?= $exibirValor($nome) ?>
                                            </div>
                                        </div>

                                        <div class="usuario-mobile-info">
                                            <div class="usuario-mobile-linha">
                                                <span class="usuario-mobile-label">Matrícula</span>
                                                <span class="usuario-mobile-valor">
                                                    <?= $exibirValor($usuario["matricula"], "Não informada") ?>
                                                </span>
                                            </div>

                                            <div class="usuario-mobile-linha">
                                                <span class="usuario-mobile-label">
                                                    <?= $exibirValor($secao["coluna"]) ?>
                                                </span>
                                                <span class="usuario-mobile-valor">
                                                    <?= $exibirValor($usuario["detalhe"]) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="acesso-mobile">
                                            <span class="acesso-mobile-titulo">
                                                <i class="bi bi-power me-1"></i>Acesso
                                            </span>

                                            <?php if ($usuario["disponivel"]): ?>
                                                <form
                                                    method="POST"
                                                    action="/painel/acesso/usuario"
                                                    data-form-acesso
                                                    data-nome="<?= $exibirValor($nome) ?>">

                                                    <input type="hidden" name="id_credenciais" value="<?= $usuario["id_credenciais"] ?>">
                                                    <input type="hidden" name="ativo" value="<?= $usuario["ativo"] ?>">

                                                    <div class="acesso-container">
                                                        <div class="form-check form-switch acesso-switch">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                role="switch"
                                                                data-alterar-acesso
                                                                aria-label="Alterar acesso de <?= $exibirValor($nome) ?>"
                                                                <?= $usuario["ativo"] === 1 ? "checked" : "" ?>>
                                                        </div>

                                                        <span class="texto-acesso <?= $usuario["ativo"] === 1 ? "acesso-on" : "acesso-off" ?>">
                                                            <?= $usuario["ativo"] === 1 ? "ON" : "OFF" ?>
                                                        </span>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <span class="acesso-indisponivel">Indisponível</span>
                                            <?php endif; ?>
                                        </div>

                                    </article>
                                <?php endforeach; ?>

                            </div>

                            <div class="mobile-lista-rodape" data-rodape-mobile hidden>
                                <div class="mobile-lista-contador" data-contador-mobile></div>

                                <button
                                    type="button"
                                    class="btn btn-outline-primary mobile-mostrar-mais"
                                    data-mostrar-mais
                                    hidden>
                                    <i class="bi bi-chevron-down me-1"></i>Mostrar mais 5
                                </button>
                            </div>
                        </div>

                        <div class="text-center text-secondary small py-4" data-sem-resultados hidden>
                            <i class="bi bi-search fs-3 d-block mb-2"></i>
                            Nenhum usuário corresponde à pesquisa.
                        </div>

                    <?php endif; ?>

                </section>

            <?php endforeach; ?>

        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const normalizar = texto => String(texto || "")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim();

        document.querySelectorAll(".painel-acesso [data-painel-usuarios]").forEach(painel => {
            const busca = painel.querySelector("[data-busca-acesso]");
            const linhas = [...painel.querySelectorAll("[data-linha-usuario]")];
            const cards = [...painel.querySelectorAll("[data-card-usuario]")];
            const botaoMais = painel.querySelector("[data-mostrar-mais]");
            const contador = painel.querySelector("[data-contador-mobile]");
            const rodape = painel.querySelector("[data-rodape-mobile]");
            const semResultados = painel.querySelector("[data-sem-resultados]");

            if (!busca) return;

            let limiteMobile = 5;

            function atualizarLista() {
                const termo = normalizar(busca.value);
                let encontrados = 0;
                let exibidos = 0;

                linhas.forEach(linha => {
                    const corresponde = normalizar(linha.dataset.busca).includes(termo);
                    linha.hidden = !corresponde;
                });

                cards.forEach(card => {
                    const corresponde = normalizar(card.dataset.busca).includes(termo);

                    if (corresponde) {
                        encontrados++;

                        if (exibidos < limiteMobile) {
                            card.hidden = false;
                            exibidos++;
                        } else {
                            card.hidden = true;
                        }
                    } else {
                        card.hidden = true;
                    }
                });

                if (semResultados) semResultados.hidden = encontrados !== 0;
                if (rodape) rodape.hidden = encontrados === 0;

                if (contador) {
                    contador.textContent = "Exibindo " + exibidos + " de " + encontrados + " registros";
                }

                if (botaoMais) {
                    botaoMais.hidden = encontrados <= limiteMobile;
                    const restantes = Math.max(0, encontrados - limiteMobile);
                    botaoMais.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Mostrar mais ' + Math.min(5, restantes);
                }
            }

            busca.addEventListener("input", () => {
                limiteMobile = 5;
                atualizarLista();
            });

            if (botaoMais) {
                botaoMais.addEventListener("click", () => {
                    limiteMobile += 5;
                    atualizarLista();
                });
            }

            atualizarLista();
        });

        document.querySelectorAll(".painel-acesso [data-form-acesso]").forEach(form => {
            form.addEventListener("submit", event => {
                if (form.dataset.enviando === "1") {
                    event.preventDefault();
                    return;
                }
                form.dataset.enviando = "1";
            });
        });

        document.querySelectorAll(".painel-acesso [data-alterar-acesso]").forEach(input => {
            input.addEventListener("change", () => {
                const form = input.closest("form");
                if (!form || form.dataset.enviando === "1") return;

                const nome = form.dataset.nome || "este usuário";
                const campoAtivo = form.querySelector('input[name="ativo"]');

                if (!campoAtivo) return;

                const novoStatus = input.checked ? 1 : 0;
                const mensagem = novoStatus === 1 ?
                    "Deseja liberar o acesso de " + nome + "?" :
                    "Deseja bloquear o acesso de " + nome + "?";

                if (!confirm(mensagem)) {
                    input.checked = !input.checked;
                    return;
                }

                campoAtivo.value = novoStatus;
                input.disabled = true;
                form.requestSubmit();
            });
        });
    });
</script>