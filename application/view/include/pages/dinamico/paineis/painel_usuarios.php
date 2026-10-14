<?php
$administradores = $model["administradores"] ?? [];
$alunos = $model["alunos"] ?? [];

$administradores = is_iterable($administradores) ? $administradores : [];
$alunos = is_iterable($alunos) ? $alunos : [];

$cargos = [
    1 => "Professor",
    2 => "Administração",
    3 => "Diretor Geral",
    4 => "ADM do Sistema"
];

$series = [
    1 => "1º Ano Integral",
    2 => "1º Ano Técnico",
    3 => "2º Ano Integral",
    4 => "2º Ano Técnico",
    5 => "3º Ano Integral",
    6 => "3º Ano Técnico",
    9 => "EJA"
];

$exibirValor = static function ($valor, string $padrao = "-"): string {
    $valor = trim((string)($valor ?? ""));
    return htmlspecialchars(
        $valor !== "" ? $valor : $padrao,
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
};

$resolverNome = static function (
    object $registro,
    array $campos,
    array $mapa,
    string $rotulo
): string {
    foreach ($campos as $campo) {
        $valor = trim((string)($registro->$campo ?? ""));

        if ($valor === "") continue;

        if (ctype_digit($valor)) {
            $id = (int)$valor;
            return $mapa[$id] ?? $rotulo . " não cadastrado (ID " . $id . ")";
        }

        return $valor;
    }

    return "Não informado";
};

$prepararUsuarios = static function ($lista, string $tipo) use ($resolverNome, $cargos, $series): array {
    $resultado = [];

    foreach ($lista as $registro) {
        if (!is_object($registro) && !is_array($registro)) continue;

        $registro = (object)$registro;
        $nome = trim((string)($registro->nome ?? ""));
        $matricula = trim((string)($registro->matricula ?? ""));

        if ($tipo === "administrador") {
            $detalhe = $resolverNome(
                $registro,
                ["cargo", "nome_tipo", "tipo", "id_tipo_adm"],
                $cargos,
                "Cargo"
            );
        } else {
            $detalhe = $resolverNome(
                $registro,
                ["nome_serie", "serie", "id_serie"],
                $series,
                "Série"
            );
        }

        $ativo = isset($registro->ativo) ? (int)$registro->ativo : 1;

        $resultado[] = [
            "id" => (int)($registro->id ?? 0),
            "nome" => $nome !== "" ? $nome : "Nome não informado",
            "matricula" => $matricula,
            "detalhe" => $detalhe,
            "ativo" => $ativo === 1,
            "status" => $ativo === 1 ? "Ativo" : "Inativo",
            "tipo" => $tipo
        ];
    }

    return $resultado;
};

$administradores = $prepararUsuarios($administradores, "administrador");
$alunos = $prepararUsuarios($alunos, "aluno");

$secoes = [];

if ((int)($_SESSION["acesso"] ?? 0) > 1) {
    $secoes[] = [
        "id" => "administradores",
        "titulo" => "Administradores",
        "icone" => "bi-person-badge-fill",
        "coluna" => "Cargo",
        "usuarios" => $administradores,
        "tipo" => "administrador"
    ];
}

$secoes[] = [
    "id" => "alunos",
    "titulo" => "Alunos",
    "icone" => "bi-mortarboard-fill",
    "coluna" => "Série",
    "usuarios" => $alunos,
    "tipo" => "aluno"
];
?>

<main class="container-fluid py-3 py-md-4 painel-usuarios">
    <style>
        .painel-usuarios .painel-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .painel-usuarios .painel-cabecalho h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .painel-usuarios .painel-acoes {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-shrink: 0;
        }

        .painel-usuarios .usuario-card {
            border: 0;
            border-radius: .8rem;
            overflow: hidden;
        }

        .painel-usuarios .usuario-card-header {
            background: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .painel-usuarios .usuario-card-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .painel-usuarios .quantidade-registros {
            background: rgba(255, 255, 255, .2);
            border-radius: 2rem;
            font-size: .75rem;
            padding: .2rem .55rem;
            white-space: nowrap;
        }

        .painel-usuarios .barra-busca {
            background: #fff;
            padding: .85rem 1rem;
            border-bottom: 1px solid #edf0f4;
        }

        .painel-usuarios .barra-busca .input-group {
            max-width: 460px;
        }

        .painel-usuarios .barra-busca .form-control,
        .painel-usuarios .barra-busca .input-group-text {
            border-color: #dce3ed;
        }

        .painel-usuarios .tabela-scroll {
            max-height: 350px;
            overflow: auto;
        }

        .painel-usuarios .tabela-usuarios {
            min-width: 820px;
            margin-bottom: 0;
        }

        .painel-usuarios .tabela-usuarios thead th {
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

        .painel-usuarios .tabela-usuarios th:not(:last-child),
        .painel-usuarios .tabela-usuarios td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .painel-usuarios .tabela-usuarios tbody td {
            background: #fff;
            vertical-align: middle;
        }

        .painel-usuarios .tabela-usuarios tbody tr {
            height: 58px;
        }

        .painel-usuarios .tabela-usuarios tbody tr:hover td {
            background: #f8fbff;
        }

        .painel-usuarios .nome-usuario {
            font-weight: 600;
            color: #212529;
            overflow-wrap: anywhere;
        }

        .painel-usuarios .badge-status {
            min-width: 82px;
            padding: .4rem .6rem;
        }

        .painel-usuarios .acoes-usuario {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            white-space: nowrap;
        }

        .painel-usuarios .acoes-usuario form {
            margin: 0;
        }

        .painel-usuarios .estado-vazio {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .painel-usuarios .estado-vazio i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .painel-usuarios .cards-mobile {
            display: none;
            background: #f8fafc;
        }

        .painel-usuarios .cards-mobile-lista {
            display: grid;
            grid-template-columns: 1fr;
            gap: .75rem;
            padding: .85rem;
        }

        .painel-usuarios .usuario-mobile-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            padding: .95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
            min-width: 0;
        }

        .painel-usuarios .usuario-mobile-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .85rem;
        }

        .painel-usuarios .usuario-mobile-identificacao {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            min-width: 0;
        }

        .painel-usuarios .usuario-mobile-icone {
            width: 36px;
            height: 36px;
            min-width: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #0d6efd;
            background: #eaf2ff;
            border-radius: 50%;
        }

        .painel-usuarios .usuario-mobile-nome {
            font-size: .95rem;
            font-weight: 700;
            color: #263345;
            overflow-wrap: anywhere;
        }

        .painel-usuarios .usuario-mobile-id {
            font-size: .75rem;
            color: #6c757d;
            margin-top: .15rem;
        }

        .painel-usuarios .usuario-mobile-info {
            display: grid;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .painel-usuarios .usuario-mobile-linha {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: .85rem;
            padding-bottom: .45rem;
            border-bottom: 1px solid #f0f2f5;
        }

        .painel-usuarios .usuario-mobile-linha:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .painel-usuarios .usuario-mobile-label {
            font-size: .8rem;
            color: #6c757d;
            flex-shrink: 0;
        }

        .painel-usuarios .usuario-mobile-valor {
            font-size: .84rem;
            font-weight: 600;
            color: #263345;
            text-align: right;
            overflow-wrap: anywhere;
            min-width: 0;
        }

        .painel-usuarios .usuario-mobile-acoes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .55rem;
            border-top: 1px solid #edf0f4;
            padding-top: .85rem;
            margin-top: auto;
        }

        .painel-usuarios .usuario-mobile-acoes .btn {
            width: 100%;
            min-height: 39px;
            font-size: .85rem;
            font-weight: 600;
        }

        .painel-usuarios .usuario-mobile-acoes form {
            margin: 0;
        }

        .painel-usuarios .mobile-lista-rodape {
            text-align: center;
            padding: .4rem .85rem 1rem;
        }

        .painel-usuarios .mobile-lista-contador {
            font-size: .78rem;
            color: #6c757d;
            margin-bottom: .65rem;
        }

        .painel-usuarios .mobile-mostrar-mais {
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            padding: .55rem 1.25rem;
        }

        .painel-usuarios .sem-resultados {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .painel-usuarios [hidden] {
            display: none !important;
        }

        .painel-usuarios .tabela-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .painel-usuarios .tabela-scroll::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }

        .painel-usuarios .tabela-scroll::-webkit-scrollbar-track {
            background: #f1f3f5;
        }

        @media(max-width:767.98px) {
            .painel-usuarios .painel-cabecalho {
                flex-direction: column;
                align-items: stretch;
            }

            .painel-usuarios .painel-cabecalho h1 {
                font-size: 1.35rem;
            }

            .painel-usuarios .painel-acoes {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .painel-usuarios .painel-acoes .btn {
                width: 100%;
            }

            .painel-usuarios .tabela-desktop {
                display: none;
            }

            .painel-usuarios .cards-mobile {
                display: block;
            }

            .painel-usuarios .barra-busca .input-group {
                max-width: none;
            }
        }

        @media(min-width:600px) and (max-width:767.98px) {
            .painel-usuarios .cards-mobile-lista {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width:575.98px) {
            .painel-usuarios .painel-acoes {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">

            <div class="painel-cabecalho">
                <div>
                    <h1>
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Gerenciamento de Usuários
                    </h1>
                    <div class="text-body-secondary mt-1">
                        Gerencie administradores e alunos cadastrados no sistema.
                    </div>
                </div>

                <div class="painel-acoes">
                    <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>

                    <a href="/cadastro/usuario" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-1"></i>Cadastrar usuário
                    </a>
                </div>
            </div>

            <?php foreach ($secoes as $secao): ?>
                <?php
                $usuarios = $secao["usuarios"];
                $quantidade = count($usuarios);
                $ehAdministrador = $secao["tipo"] === "administrador";
                ?>

                <section class="card shadow-sm usuario-card mb-4" data-secao-usuarios>
                    <div class="usuario-card-header">
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
                            <strong>
                                Nenhum <?= $ehAdministrador ? "administrador" : "aluno" ?> cadastrado
                            </strong>
                            <div class="small mt-1">
                                Não existem registros disponíveis no momento.
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
                                    placeholder="Pesquisar por nome, matrícula ou <?= $ehAdministrador ? 'cargo' : 'série' ?>..."
                                    aria-label="Pesquisar <?= $exibirValor($secao["titulo"]) ?>"
                                    autocomplete="off"
                                    data-busca-usuarios>
                            </div>
                        </div>

                        <div class="tabela-scroll tabela-desktop">
                            <table class="table table-hover tabela-usuarios">
                                <thead>
                                    <tr>
                                        <th class="ps-3">ID</th>
                                        <th>Nome</th>
                                        <th>Matrícula</th>
                                        <th><?= $exibirValor($secao["coluna"]) ?></th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center pe-3">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <?php
                                        $id = $usuario["id"];
                                        $nome = $usuario["nome"];
                                        $tipo = $usuario["tipo"];
                                        $rotaEdicao = $tipo === "administrador"
                                            ? "/atualizar/usuario/administrador"
                                            : "/atualizar/usuario/aluno";

                                        $termosBusca = implode(" ", [
                                            $id,
                                            $nome,
                                            $usuario["matricula"],
                                            $usuario["detalhe"],
                                            $usuario["status"]
                                        ]);
                                        ?>

                                        <tr data-linha-usuario data-busca="<?= $exibirValor($termosBusca) ?>">
                                            <td class="ps-3">
                                                <?= $id ?>
                                            </td>

                                            <td>
                                                <div class="nome-usuario">
                                                    <?= $exibirValor($nome) ?>
                                                </div>
                                            </td>

                                            <td>
                                                <?= $exibirValor($usuario["matricula"], "Não informada") ?>
                                            </td>

                                            <td>
                                                <i class="bi <?= $ehAdministrador ? 'bi-person-badge' : 'bi-mortarboard' ?> text-primary me-1"></i>
                                                <?= $exibirValor($usuario["detalhe"]) ?>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-status text-bg-<?= $usuario["ativo"] ? 'success' : 'danger' ?>">
                                                    <i class="bi <?= $usuario["ativo"] ? 'bi-check-circle' : 'bi-x-circle' ?> me-1"></i>
                                                    <?= $usuario["status"] ?>
                                                </span>
                                            </td>

                                            <td class="pe-3">
                                                <div class="acoes-usuario">
                                                    <a
                                                        href="<?= $rotaEdicao ?>?id=<?= $id ?>"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="bi bi-pencil-square me-1"></i>Editar
                                                    </a>

                                                    <form
                                                        method="POST"
                                                        action="/usuarios/excluir"
                                                        data-form-exclusao
                                                        data-tipo="<?= $tipo ?>"
                                                        data-nome="<?= $exibirValor($nome) ?>">

                                                        <input type="hidden" name="id" value="<?= $id ?>">
                                                        <input type="hidden" name="tipo_usuario" value="<?= $tipo ?>">

                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash-fill me-1"></i>Excluir
                                                        </button>
                                                    </form>
                                                </div>
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
                                    $id = $usuario["id"];
                                    $nome = $usuario["nome"];
                                    $tipo = $usuario["tipo"];
                                    $rotaEdicao = $tipo === "administrador"
                                        ? "/atualizar/usuario/administrador"
                                        : "/atualizar/usuario/aluno";

                                    $termosBusca = implode(" ", [
                                        $id,
                                        $nome,
                                        $usuario["matricula"],
                                        $usuario["detalhe"],
                                        $usuario["status"]
                                    ]);
                                    ?>

                                    <article
                                        class="usuario-mobile-card"
                                        data-card-usuario
                                        data-busca="<?= $exibirValor($termosBusca) ?>">

                                        <div class="usuario-mobile-topo">
                                            <div class="usuario-mobile-identificacao">
                                                <div class="usuario-mobile-icone">
                                                    <i class="bi <?= $ehAdministrador ? 'bi-person-badge' : 'bi-person' ?>"></i>
                                                </div>

                                                <div style="min-width:0">
                                                    <div class="usuario-mobile-nome">
                                                        <?= $exibirValor($nome) ?>
                                                    </div>
                                                    <div class="usuario-mobile-id">
                                                        ID: <?= $id ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="badge text-bg-<?= $usuario["ativo"] ? 'success' : 'danger' ?> flex-shrink-0">
                                                <?= $usuario["status"] ?>
                                            </span>
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

                                        <div class="usuario-mobile-acoes">
                                            <a
                                                href="<?= $rotaEdicao ?>?id=<?= $id ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square me-1"></i>Editar
                                            </a>

                                            <form
                                                method="POST"
                                                action="/usuarios/excluir"
                                                data-form-exclusao
                                                data-tipo="<?= $tipo ?>"
                                                data-nome="<?= $exibirValor($nome) ?>">

                                                <input type="hidden" name="id" value="<?= $id ?>">
                                                <input type="hidden" name="tipo_usuario" value="<?= $tipo ?>">

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash-fill me-1"></i>Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>

                            <div class="mobile-lista-rodape" data-rodape-mobile>
                                <div class="mobile-lista-contador" data-contador-mobile aria-live="polite"></div>

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
        const painel = document.querySelector(".painel-usuarios");
        if (!painel) return;

        const normalizar = texto => String(texto || "")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim();

        painel.querySelectorAll("[data-secao-usuarios]").forEach(secao => {
            const busca = secao.querySelector("[data-busca-usuarios]");
            const linhas = [...secao.querySelectorAll("[data-linha-usuario]")];
            const cards = [...secao.querySelectorAll("[data-card-usuario]")];
            const botaoMais = secao.querySelector("[data-mostrar-mais]");
            const textoMais = secao.querySelector("[data-texto-mais]");
            const contador = secao.querySelector("[data-contador-mobile]");
            const rodape = secao.querySelector("[data-rodape-mobile]");
            const semResultados = secao.querySelector("[data-sem-resultados]");

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
                        "Exibindo " + exibidos + " de " + encontrados + " usuários";
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

        painel.querySelectorAll("[data-form-exclusao]").forEach(form => {
            form.addEventListener("submit", event => {
                if (form.dataset.enviando === "1") {
                    event.preventDefault();
                    return;
                }

                const tipo = form.dataset.tipo === "administrador" ?
                    "administrador" :
                    "aluno";

                const nome = form.dataset.nome || "este usuário";

                const confirmado = confirm(
                    'Deseja realmente excluir o ' + tipo + ' "' + nome + '"?\n\nEssa ação não poderá ser desfeita.'
                );

                if (!confirmado) {
                    event.preventDefault();
                    return;
                }

                form.dataset.enviando = "1";

                const botao = form.querySelector('button[type="submit"]');
                if (botao) botao.disabled = true;
            });
        });

        window.addEventListener("pageshow", event => {
            if (!event.persisted) return;

            painel.querySelectorAll("[data-form-exclusao]").forEach(form => {
                delete form.dataset.enviando;
                const botao = form.querySelector('button[type="submit"]');
                if (botao) botao.disabled = false;
            });
        });
    });
</script>