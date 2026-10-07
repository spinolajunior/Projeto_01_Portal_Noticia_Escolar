<?php

$administradores = $model["adm"] ?? [];
$alunos = $model["alunos"] ?? [];

if (!is_iterable($administradores)) {
    $administradores = [];
}

if (!is_iterable($alunos)) {
    $alunos = [];
}

$quantidadeAdministradores = is_countable($administradores)
    ? count($administradores)
    : 0;

$quantidadeAlunos = is_countable($alunos)
    ? count($alunos)
    : 0;

$exibirValor = static function (
    $valor,
    string $padrao = "-"
): string {

    if ($valor === null || $valor === "") {
        return $padrao;
    }

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );
};

?>

<main class="container-fluid py-3 py-md-4">

    <style>
        .painel-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .painel-cabecalho h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .painel-acoes {
            display: flex;
            gap: .6rem;
        }

        .card-acesso {
            border: 0;
            border-radius: .8rem;
            overflow: hidden;
        }

        .card-acesso-header {
            background-color: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .card-acesso-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .quantidade-registros {
            background-color: rgba(255, 255, 255, .2);
            padding: .2rem .55rem;
            border-radius: 2rem;
            font-size: .75rem;
            white-space: nowrap;
        }

        .tabela-scroll {
            max-height: 338px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .tabela-acesso {
            margin-bottom: 0;
            min-width: 650px;
        }

        .tabela-acesso thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            height: 48px;
            background-color: #e9f2ff;
            color: #212529;
            border-bottom: 2px solid #0d6efd;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
        }

        .tabela-acesso th:not(:last-child),
        .tabela-acesso td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .tabela-acesso thead th:not(:last-child) {
            border-right: 1px solid #c7d7ee;
        }

        .tabela-acesso tbody tr {
            height: 58px;
        }

        .tabela-acesso tbody td {
            background-color: #fff;
            vertical-align: middle;
        }

        .tabela-acesso tbody tr:hover td {
            background-color: #f8fbff;
        }

        .nome-usuario {
            font-weight: 600;
            color: #212529;
        }

        .acesso-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .65rem;
        }

        .acesso-switch {
            padding-left: 0;
            margin: 0;
        }

        .acesso-switch .form-check-input {
            float: none;
            margin: 0;
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
            border-color: #adb5bd;
        }

        .acesso-switch .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .acesso-switch .form-check-input:not(:checked) {
            background-color: #dee2e6;
        }

        .acesso-switch .form-check-input:focus {
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15);
        }

        .texto-acesso {
            min-width: 32px;
            font-size: .78rem;
            font-weight: 700;
        }

        .acesso-on {
            color: #198754;
        }

        .acesso-off {
            color: #6c757d;
        }

        .acesso-indisponivel {
            color: #adb5bd;
            font-size: .82rem;
            font-weight: 600;
        }

        .estado-vazio {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .estado-vazio i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .cards-mobile {
            display: none;
        }

        .cards-mobile-scroll {
            background-color: #f8f9fa;
        }

        .usuario-mobile-card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: .75rem;
            padding: 1rem;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .usuario-mobile-card+.usuario-mobile-card {
            margin-top: .75rem;
        }

        .usuario-mobile-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .usuario-mobile-nome {
            font-size: 1rem;
            font-weight: 700;
            color: #212529;
        }

        .usuario-mobile-info {
            display: grid;
            gap: .6rem;
            margin-bottom: 1rem;
        }

        .usuario-mobile-linha {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: .45rem;
        }

        .usuario-mobile-linha:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .usuario-mobile-label {
            font-size: .82rem;
            color: #6c757d;
            font-weight: 500;
        }

        .usuario-mobile-valor {
            font-size: .85rem;
            font-weight: 600;
            color: #212529;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .acesso-mobile {
            background-color: #f8f9fa;
            border-radius: .65rem;
            padding: .8rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .acesso-mobile-titulo {
            font-size: .85rem;
            font-weight: 600;
            color: #495057;
        }

        .tabela-scroll::-webkit-scrollbar,
        .cards-mobile-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .tabela-scroll::-webkit-scrollbar-thumb,
        .cards-mobile-scroll::-webkit-scrollbar-thumb {
            background-color: #adb5bd;
            border-radius: 10px;
        }

        .tabela-scroll::-webkit-scrollbar-track,
        .cards-mobile-scroll::-webkit-scrollbar-track {
            background-color: #f1f3f5;
        }

        @media (max-width: 767.98px) {

            .painel-cabecalho {
                flex-direction: column;
                align-items: stretch;
            }

            .painel-cabecalho h1 {
                font-size: 1.35rem;
            }

            .painel-acoes {
                display: grid;
                grid-template-columns: 1fr;
            }

            .painel-acoes .btn {
                width: 100%;
            }

            .tabela-desktop {
                display: none;
            }

            .cards-mobile {
                display: block;
            }

            .cards-mobile-scroll {
                max-height: 570px;
                overflow-y: auto;
                padding: .75rem;
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
                        Ative ou desative rapidamente o acesso dos usuários ao sistema.
                    </div>

                </div>

                <div class="painel-acoes">

                    <a
                        href="/painel/gerenciamento"
                        class="btn btn-outline-secondary"
                        onclick="return voltarPainel(event);">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>

                </div>

            </div>

            <div class="card shadow-sm card-acesso mb-4">

                <div class="card-acesso-header">

                    <h2>
                        <i class="bi bi-person-badge-fill me-2"></i>
                        Administradores
                    </h2>

                    <span class="quantidade-registros">
                        <?= $quantidadeAdministradores ?>
                        <?= $quantidadeAdministradores === 1
                            ? "registro"
                            : "registros" ?>
                    </span>

                </div>

                <?php if ($quantidadeAdministradores === 0): ?>

                    <div class="estado-vazio">

                        <i class="bi bi-person-x"></i>

                        <strong>
                            Nenhum administrador encontrado
                        </strong>

                        <div class="small mt-1">
                            Não existem administradores cadastrados no momento.
                        </div>

                    </div>

                <?php else: ?>

                    <div class="tabela-scroll tabela-desktop">

                        <table class="table table-hover tabela-acesso">

                            <thead>

                                <tr>
                                    <th>Nome</th>
                                    <th>Matrícula</th>
                                    <th>Cargo</th>
                                    <th class="text-center">Acesso</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($administradores as $adm): ?>

                                    <?php

                                    $idCredenciais =
                                        $adm->id_credenciais ?? null;

                                    $nome =
                                        $adm->nome ?? "";

                                    $matricula =
                                        $adm->matricula ?? "";

                                    $cargo =
                                        $adm->cargo
                                        ?? $adm->nome_tipo
                                        ?? $adm->tipo
                                        ?? "-";

                                    $ativo = isset($adm->ativo)
                                        ? (int) $adm->ativo
                                        : null;

                                    ?>

                                    <tr>

                                        <td>

                                            <div class="nome-usuario">

                                                <i class="bi bi-person-circle text-primary me-1"></i>

                                                <?= $exibirValor($nome) ?>

                                            </div>

                                        </td>

                                        <td>

                                            <?= $exibirValor(
                                                $matricula,
                                                "Não informada"
                                            ) ?>

                                        </td>

                                        <td>

                                            <i class="bi bi-person-badge text-primary me-1"></i>

                                            <?= $exibirValor($cargo) ?>

                                        </td>

                                        <td class="text-center">

                                            <?php if (
                                                $idCredenciais !== null &&
                                                $ativo !== null
                                            ): ?>

                                                <form
                                                    method="POST"
                                                    action="/painel/acesso/usuario"
                                                    class="d-inline"
                                                    data-nome="<?= htmlspecialchars(
                                                                    $nome,
                                                                    ENT_QUOTES,
                                                                    "UTF-8"
                                                                ) ?>">

                                                    <input
                                                        type="hidden"
                                                        name="id_credenciais"
                                                        value="<?= (int) $idCredenciais ?>">

                                                    <input
                                                        type="hidden"
                                                        name="ativo"
                                                        value="<?= $ativo ?>">

                                                    <div class="acesso-container">

                                                        <div class="form-check form-switch acesso-switch">

                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                role="switch"
                                                                <?= $ativo === 1
                                                                    ? "checked"
                                                                    : "" ?>
                                                                onchange="alterarAcesso(this);">

                                                        </div>

                                                        <span
                                                            class="texto-acesso <?= $ativo === 1
                                                                                    ? "acesso-on"
                                                                                    : "acesso-off" ?>">
                                                            <?= $ativo === 1
                                                                ? "ON"
                                                                : "OFF" ?>
                                                        </span>

                                                    </div>

                                                </form>

                                            <?php else: ?>

                                                <span class="acesso-indisponivel">
                                                    Indisponível
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="cards-mobile">

                        <div class="cards-mobile-scroll">

                            <?php foreach ($administradores as $adm): ?>

                                <?php

                                $idCredenciais =
                                    $adm->id_credenciais ?? null;

                                $nome =
                                    $adm->nome ?? "";

                                $matricula =
                                    $adm->matricula ?? "";

                                $cargo =
                                    $adm->cargo
                                    ?? $adm->nome_tipo
                                    ?? $adm->tipo
                                    ?? "-";

                                $ativo = isset($adm->ativo)
                                    ? (int) $adm->ativo
                                    : null;

                                ?>

                                <div class="usuario-mobile-card">

                                    <div class="usuario-mobile-topo">

                                        <div class="usuario-mobile-nome">

                                            <i class="bi bi-person-circle text-primary me-1"></i>

                                            <?= $exibirValor($nome) ?>

                                        </div>

                                    </div>

                                    <div class="usuario-mobile-info">

                                        <div class="usuario-mobile-linha">

                                            <span class="usuario-mobile-label">
                                                Matrícula
                                            </span>

                                            <span class="usuario-mobile-valor">

                                                <?= $exibirValor(
                                                    $matricula,
                                                    "Não informada"
                                                ) ?>

                                            </span>

                                        </div>

                                        <div class="usuario-mobile-linha">

                                            <span class="usuario-mobile-label">
                                                Cargo
                                            </span>

                                            <span class="usuario-mobile-valor">
                                                <?= $exibirValor($cargo) ?>
                                            </span>

                                        </div>

                                    </div>

                                    <div class="acesso-mobile">

                                        <span class="acesso-mobile-titulo">
                                            <i class="bi bi-power me-1"></i>
                                            Acesso
                                        </span>

                                        <?php if (
                                            $idCredenciais !== null &&
                                            $ativo !== null
                                        ): ?>

                                            <form
                                                method="POST"
                                                action="/usuarios/status"
                                                data-nome="<?= htmlspecialchars(
                                                                $nome,
                                                                ENT_QUOTES,
                                                                "UTF-8"
                                                            ) ?>">

                                                <input
                                                    type="hidden"
                                                    name="id_credenciais"
                                                    value="<?= (int) $idCredenciais ?>">

                                                <input
                                                    type="hidden"
                                                    name="ativo"
                                                    value="<?= $ativo ?>">

                                                <div class="acesso-container">

                                                    <div class="form-check form-switch acesso-switch">

                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            <?= $ativo === 1
                                                                ? "checked"
                                                                : "" ?>
                                                            onchange="alterarAcesso(this);">

                                                    </div>

                                                    <span
                                                        class="texto-acesso <?= $ativo === 1
                                                                                ? "acesso-on"
                                                                                : "acesso-off" ?>">
                                                        <?= $ativo === 1
                                                            ? "ON"
                                                            : "OFF" ?>
                                                    </span>

                                                </div>

                                            </form>

                                        <?php else: ?>

                                            <span class="acesso-indisponivel">
                                                N/D
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

            <div class="card shadow-sm card-acesso">

                <div class="card-acesso-header">

                    <h2>
                        <i class="bi bi-mortarboard-fill me-2"></i>
                        Alunos
                    </h2>

                    <span class="quantidade-registros">
                        <?= $quantidadeAlunos ?>
                        <?= $quantidadeAlunos === 1
                            ? "registro"
                            : "registros" ?>
                    </span>

                </div>

                <?php if ($quantidadeAlunos === 0): ?>

                    <div class="estado-vazio">

                        <i class="bi bi-person-x"></i>

                        <strong>
                            Nenhum aluno encontrado
                        </strong>

                        <div class="small mt-1">
                            Não existem alunos cadastrados no momento.
                        </div>

                    </div>

                <?php else: ?>

                    <div class="tabela-scroll tabela-desktop">

                        <table class="table table-hover tabela-acesso">

                            <thead>

                                <tr>
                                    <th>Nome</th>
                                    <th>Matrícula</th>
                                    <th>Série</th>
                                    <th class="text-center">Acesso</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($alunos as $aluno): ?>

                                    <?php

                                    $idCredenciais =
                                        $aluno->id_credenciais ?? null;

                                    $nome =
                                        $aluno->nome ?? "";

                                    $matricula =
                                        $aluno->matricula ?? "";

                                    $serie =
                                        $aluno->serie
                                        ?? $aluno->nome_serie
                                        ?? $aluno->id_serie
                                        ?? "-";

                                    $ativo = isset($aluno->ativo)
                                        ? (int) $aluno->ativo
                                        : null;

                                    ?>

                                    <tr>

                                        <td>

                                            <div class="nome-usuario">

                                                <i class="bi bi-person-circle text-primary me-1"></i>

                                                <?= $exibirValor($nome) ?>

                                            </div>

                                        </td>

                                        <td>
                                            <?= $exibirValor($matricula) ?>
                                        </td>

                                        <td>

                                            <i class="bi bi-mortarboard text-primary me-1"></i>

                                            <?= $exibirValor($serie) ?>

                                        </td>

                                        <td class="text-center">

                                            <?php if (
                                                $idCredenciais !== null &&
                                                $ativo !== null
                                            ): ?>

                                                <form
                                                    method="POST"
                                                    action="/usuarios/status"
                                                    class="d-inline"
                                                    data-nome="<?= htmlspecialchars(
                                                                    $nome,
                                                                    ENT_QUOTES,
                                                                    "UTF-8"
                                                                ) ?>">

                                                    <input
                                                        type="hidden"
                                                        name="id_credenciais"
                                                        value="<?= (int) $idCredenciais ?>">

                                                    <input
                                                        type="hidden"
                                                        name="ativo"
                                                        value="<?= $ativo ?>">

                                                    <div class="acesso-container">

                                                        <div class="form-check form-switch acesso-switch">

                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                role="switch"
                                                                <?= $ativo === 1
                                                                    ? "checked"
                                                                    : "" ?>
                                                                onchange="alterarAcesso(this);">

                                                        </div>

                                                        <span
                                                            class="texto-acesso <?= $ativo === 1
                                                                                    ? "acesso-on"
                                                                                    : "acesso-off" ?>">
                                                            <?= $ativo === 1
                                                                ? "ON"
                                                                : "OFF" ?>
                                                        </span>

                                                    </div>

                                                </form>

                                            <?php else: ?>

                                                <span class="acesso-indisponivel">
                                                    Indisponível
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="cards-mobile">

                        <div class="cards-mobile-scroll">

                            <?php foreach ($alunos as $aluno): ?>

                                <?php

                                $idCredenciais =
                                    $aluno->id_credenciais ?? null;

                                $nome =
                                    $aluno->nome ?? "";

                                $matricula =
                                    $aluno->matricula ?? "";

                                $serie =
                                    $aluno->serie
                                    ?? $aluno->nome_serie
                                    ?? $aluno->id_serie
                                    ?? "-";

                                $ativo = isset($aluno->ativo)
                                    ? (int) $aluno->ativo
                                    : null;

                                ?>

                                <div class="usuario-mobile-card">

                                    <div class="usuario-mobile-topo">

                                        <div class="usuario-mobile-nome">

                                            <i class="bi bi-person-circle text-primary me-1"></i>

                                            <?= $exibirValor($nome) ?>

                                        </div>

                                    </div>

                                    <div class="usuario-mobile-info">

                                        <div class="usuario-mobile-linha">

                                            <span class="usuario-mobile-label">
                                                Matrícula
                                            </span>

                                            <span class="usuario-mobile-valor">
                                                <?= $exibirValor($matricula) ?>
                                            </span>

                                        </div>

                                        <div class="usuario-mobile-linha">

                                            <span class="usuario-mobile-label">
                                                Série
                                            </span>

                                            <span class="usuario-mobile-valor">
                                                <?= $exibirValor($serie) ?>
                                            </span>

                                        </div>

                                    </div>

                                    <div class="acesso-mobile">

                                        <span class="acesso-mobile-titulo">
                                            <i class="bi bi-power me-1"></i>
                                            Acesso
                                        </span>

                                        <?php if (
                                            $idCredenciais !== null &&
                                            $ativo !== null
                                        ): ?>

                                            <form
                                                method="POST"
                                                action="/usuarios/status"
                                                data-nome="<?= htmlspecialchars(
                                                                $nome,
                                                                ENT_QUOTES,
                                                                "UTF-8"
                                                            ) ?>">

                                                <input
                                                    type="hidden"
                                                    name="id_credenciais"
                                                    value="<?= (int) $idCredenciais ?>">

                                                <input
                                                    type="hidden"
                                                    name="ativo"
                                                    value="<?= $ativo ?>">

                                                <div class="acesso-container">

                                                    <div class="form-check form-switch acesso-switch">

                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            <?= $ativo === 1
                                                                ? "checked"
                                                                : "" ?>
                                                            onchange="alterarAcesso(this);">

                                                    </div>

                                                    <span
                                                        class="texto-acesso <?= $ativo === 1
                                                                                ? "acesso-on"
                                                                                : "acesso-off" ?>">
                                                        <?= $ativo === 1
                                                            ? "ON"
                                                            : "OFF" ?>
                                                    </span>

                                                </div>

                                            </form>

                                        <?php else: ?>

                                            <span class="acesso-indisponivel">
                                                N/D
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</main>

<script>
    function alterarAcesso(input) {

        const form = input.closest("form");
        const nome = form.dataset.nome;

        const campoAtivo = form.querySelector(
            'input[name="ativo"]'
        );

        const texto = form.querySelector(
            ".texto-acesso"
        );

        const novoStatus = input.checked ? 1 : 0;

        const mensagem = novoStatus === 1 ?
            "Deseja liberar o acesso de " + nome + "?" :
            "Deseja bloquear o acesso de " + nome + "?";

        if (!confirm(mensagem)) {

            input.checked = !input.checked;

            return;
        }

        campoAtivo.value = novoStatus;

        if (texto) {

            texto.textContent = novoStatus === 1 ?
                "ON" :
                "OFF";

            texto.classList.toggle(
                "acesso-on",
                novoStatus === 1
            );

            texto.classList.toggle(
                "acesso-off",
                novoStatus === 0
            );
        }

        form.submit();
    }

    function voltarPainel(event) {

        if (window.history.length > 1) {

            event.preventDefault();
            window.history.back();

            return false;
        }

        return true;
    }
</script>