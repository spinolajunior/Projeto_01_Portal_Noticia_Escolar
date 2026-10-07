<?php

$administradores = $model["administradores"] ?? [];
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

$exibirValor = static function ($valor, string $padrao = "-"): string {

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

        .usuario-card {
            border: 0;
            border-radius: .8rem;
            overflow: hidden;
        }

        .usuario-card-header {
            background-color: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .usuario-card-header h2 {
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

        .tabela-usuarios {
            margin-bottom: 0;
            min-width: 820px;
        }

        .tabela-usuarios thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #e9f2ff;
            color: #212529;
            font-weight: 600;
            border-bottom: 2px solid #0d6efd;
            white-space: nowrap;
            vertical-align: middle;
            height: 48px;
        }

        .tabela-usuarios th:not(:last-child),
        .tabela-usuarios td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .tabela-usuarios thead th:not(:last-child) {
            border-right: 1px solid #c7d7ee;
        }

        .tabela-usuarios tbody tr {
            height: 58px;
        }

        .tabela-usuarios tbody td {
            vertical-align: middle;
            background-color: #fff;
        }

        .tabela-usuarios tbody tr:hover td {
            background-color: #f8fbff;
        }

        .nome-usuario {
            font-weight: 600;
            color: #212529;
        }

        .badge-status {
            min-width: 78px;
        }

        .acoes-usuario {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            white-space: nowrap;
        }

        .acoes-usuario form {
            margin: 0;
        }

        .estado-vazio {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .estado-vazio i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: .75rem;
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

        .cards-mobile {
            display: none;
        }

        .usuario-mobile-card {
            border: 1px solid #dee2e6;
            border-radius: .75rem;
            background-color: #fff;
            padding: 1rem;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .usuario-mobile-card+.usuario-mobile-card {
            margin-top: .75rem;
        }

        .usuario-mobile-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .85rem;
        }

        .usuario-mobile-nome {
            font-weight: 700;
            font-size: 1rem;
            color: #212529;
        }

        .usuario-mobile-id {
            font-size: .75rem;
            color: #6c757d;
        }

        .usuario-mobile-info {
            display: grid;
            gap: .6rem;
            margin-bottom: 1rem;
        }

        .usuario-mobile-linha {
            display: flex;
            justify-content: space-between;
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

        .usuario-mobile-acoes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .5rem;
        }

        .usuario-mobile-acoes form {
            margin: 0;
        }

        .usuario-mobile-acoes .btn {
            width: 100%;
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
                grid-template-columns: 1fr 1fr;
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
                max-height: 610px;
                overflow-y: auto;
                padding: .75rem;
                background-color: #f8f9fa;
            }
        }

        @media (max-width: 575.98px) {

            .painel-acoes {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="row justify-content-center">

        <div class="col-12 col-xxl-10">

            <div class="painel-cabecalho">

                <div>

                    <h1>
                        <i class="bi bi-people-fill me-2 text-primary"></i>
                        Gerenciamento de Usuários
                    </h1>

                    <div class="text-body-secondary mt-1">
                        Gerencie administradores e alunos cadastrados no sistema.
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

                    <a
                        href="/cadastro/usuario"
                        class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-1"></i>
                        Cadastrar usuário
                    </a>

                </div>

            </div>

            <div class="card shadow-sm usuario-card mb-4">

                <div class="usuario-card-header">

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
                            Nenhum administrador cadastrado
                        </strong>

                        <div class="small mt-1">
                            Não existem administradores disponíveis no momento.
                        </div>

                    </div>

                <?php else: ?>

                    <div class="tabela-scroll tabela-desktop">

                        <table class="table table-hover tabela-usuarios">

                            <thead>

                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Matrícula</th>
                                    <th>Tipo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($administradores as $adm): ?>

                                    <?php

                                    $id = $adm->id ?? null;
                                    $nome = $adm->nome ?? null;
                                    $matricula = $adm->matricula ?? null;

                                    $tipo = $adm->tipo
                                        ?? $adm->cargo
                                        ?? $adm->nome_tipo
                                        ?? $adm->id_tipo_adm
                                        ?? "-";

                                    $ativo = isset($adm->ativo)
                                        ? (int) $adm->ativo
                                        : 1;

                                    ?>

                                    <tr>

                                        <td>
                                            <?= $exibirValor($id) ?>
                                        </td>

                                        <td>

                                            <div class="nome-usuario">
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
                                            <?= $exibirValor($tipo) ?>
                                        </td>

                                        <td class="text-center">

                                            <?php if ($ativo === 1): ?>

                                                <span class="badge text-bg-success badge-status">

                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Ativo

                                                </span>

                                            <?php else: ?>

                                                <span class="badge text-bg-danger badge-status">

                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Inativo

                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>

                                            <div class="acoes-usuario">

                                                <a
                                                    href="/atualizar/usuario/administrador?id=<?= (int) $id ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Editar
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="/usuarios/excluir"
                                                    onsubmit="return confirmarExclusao('administrador');">

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= (int) $id ?>">

                                                    <input
                                                        type="hidden"
                                                        name="tipo_usuario"
                                                        value="administrador">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash-fill me-1"></i>
                                                        Excluir
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

                        <div class="cards-mobile-scroll">

                            <?php foreach ($administradores as $adm): ?>

                                <?php

                                $id = $adm->id ?? null;
                                $nome = $adm->nome ?? null;
                                $matricula = $adm->matricula ?? null;

                                $tipo = $adm->tipo
                                    ?? $adm->cargo
                                    ?? $adm->nome_tipo
                                    ?? $adm->id_tipo_adm
                                    ?? "-";

                                $ativo = isset($adm->ativo)
                                    ? (int) $adm->ativo
                                    : 1;

                                ?>

                                <div class="usuario-mobile-card">

                                    <div class="usuario-mobile-topo">

                                        <div>

                                            <div class="usuario-mobile-nome">
                                                <?= $exibirValor($nome) ?>
                                            </div>

                                            <div class="usuario-mobile-id">
                                                ID: <?= $exibirValor($id) ?>
                                            </div>

                                        </div>

                                        <?php if ($ativo === 1): ?>

                                            <span class="badge text-bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Ativo
                                            </span>

                                        <?php else: ?>

                                            <span class="badge text-bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Inativo
                                            </span>

                                        <?php endif; ?>

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
                                                Tipo
                                            </span>

                                            <span class="usuario-mobile-valor">
                                                <?= $exibirValor($tipo) ?>
                                            </span>

                                        </div>

                                    </div>

                                    <div class="usuario-mobile-acoes">

                                        <a
                                            href="/atualizar/usuario/administrador?id=<?= (int) $id ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="/usuarios/excluir"
                                            onsubmit="return confirmarExclusao('administrador');">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $id ?>">

                                            <input
                                                type="hidden"
                                                name="tipo_usuario"
                                                value="administrador">

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash-fill me-1"></i>
                                                Excluir
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

            <div class="card shadow-sm usuario-card">

                <div class="usuario-card-header">

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
                            Nenhum aluno cadastrado
                        </strong>

                        <div class="small mt-1">
                            Não existem alunos disponíveis no momento.
                        </div>

                    </div>

                <?php else: ?>

                    <div class="tabela-scroll tabela-desktop">

                        <table class="table table-hover tabela-usuarios">

                            <thead>

                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Matrícula</th>
                                    <th>Série</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($alunos as $aluno): ?>

                                    <?php

                                    $id = $aluno->id ?? null;
                                    $nome = $aluno->nome ?? null;
                                    $matricula = $aluno->matricula ?? null;

                                    $serie = $aluno->serie
                                        ?? $aluno->nome_serie
                                        ?? $aluno->id_serie
                                        ?? "-";

                                    $ativo = isset($aluno->ativo)
                                        ? (int) $aluno->ativo
                                        : 1;

                                    ?>

                                    <tr>

                                        <td>
                                            <?= $exibirValor($id) ?>
                                        </td>

                                        <td>

                                            <div class="nome-usuario">
                                                <?= $exibirValor($nome) ?>
                                            </div>

                                        </td>

                                        <td>
                                            <?= $exibirValor($matricula) ?>
                                        </td>

                                        <td>
                                            <?= $exibirValor($serie) ?>
                                        </td>

                                        <td class="text-center">

                                            <?php if ($ativo === 1): ?>

                                                <span class="badge text-bg-success badge-status">

                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Ativo

                                                </span>

                                            <?php else: ?>

                                                <span class="badge text-bg-danger badge-status">

                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Inativo

                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>

                                            <div class="acoes-usuario">

                                                <a
                                                    href="/atualizar/usuario/aluno?id=<?= (int) $id ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Editar
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="/usuarios/excluir"
                                                    onsubmit="return confirmarExclusao('aluno');">

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= (int) $id ?>">

                                                    <input
                                                        type="hidden"
                                                        name="tipo_usuario"
                                                        value="aluno">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash-fill me-1"></i>
                                                        Excluir
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

                        <div class="cards-mobile-scroll">

                            <?php foreach ($alunos as $aluno): ?>

                                <?php

                                $id = $aluno->id ?? null;
                                $nome = $aluno->nome ?? null;
                                $matricula = $aluno->matricula ?? null;

                                $serie = $aluno->serie
                                    ?? $aluno->nome_serie
                                    ?? $aluno->id_serie
                                    ?? "-";

                                $ativo = isset($aluno->ativo)
                                    ? (int) $aluno->ativo
                                    : 1;

                                ?>

                                <div class="usuario-mobile-card">

                                    <div class="usuario-mobile-topo">

                                        <div>

                                            <div class="usuario-mobile-nome">
                                                <?= $exibirValor($nome) ?>
                                            </div>

                                            <div class="usuario-mobile-id">
                                                ID: <?= $exibirValor($id) ?>
                                            </div>

                                        </div>

                                        <?php if ($ativo === 1): ?>

                                            <span class="badge text-bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Ativo
                                            </span>

                                        <?php else: ?>

                                            <span class="badge text-bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Inativo
                                            </span>

                                        <?php endif; ?>

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

                                    <div class="usuario-mobile-acoes">

                                        <a
                                            href="/atualizar/usuario/aluno?id=<?= (int) $id ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="/usuarios/excluir"
                                            onsubmit="return confirmarExclusao('aluno');">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $id ?>">

                                            <input
                                                type="hidden"
                                                name="tipo_usuario"
                                                value="aluno">

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash-fill me-1"></i>
                                                Excluir
                                            </button>

                                        </form>

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
    function confirmarExclusao(tipo) {

        if (tipo === "aluno") {
            return confirm(
                "Tem certeza que deseja excluir este aluno?"
            );
        }

        return confirm(
            "Tem certeza que deseja excluir este administrador?"
        );
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