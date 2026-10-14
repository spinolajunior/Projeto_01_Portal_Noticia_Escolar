<?php
$logs = $model["logs"] ?? [];
if (!is_iterable($logs)) $logs = [];

$exibirValor = static function ($valor, string $padrao = "-"): string {
    if ($valor === null || $valor === "") return htmlspecialchars($padrao, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
};

$formatarData = static function ($data): string {
    if (empty($data)) return "Não definida";
    try {
        return (new DateTimeImmutable((string)$data))->format("d/m/Y H:i:s");
    } catch (Exception $e) {
        return "Não definida";
    }
};

$formatarEvento = static function ($evento): string {
    if (empty($evento)) return "Evento não informado";
    return ucwords(strtolower(str_replace("_", " ", (string)$evento)));
};

$registros = [];
foreach ($logs as $log) {
    if (!is_array($log) && !is_object($log)) continue;

    $dados = (array)$log;
    $status = strtolower(trim((string)($dados["status"] ?? "")));

    if ($status === "sucesso") {
        $statusClasse = "success";
        $statusTexto = "Sucesso";
        $statusIcone = "bi-check-circle";
    } elseif ($status === "falha" || $status === "erro") {
        $statusClasse = "danger";
        $statusTexto = $status === "erro" ? "Erro" : "Falha";
        $statusIcone = "bi-x-circle";
    } else {
        $statusClasse = "secondary";
        $statusTexto = "Indefinido";
        $statusIcone = "bi-question-circle";
    }

    $registros[] = [
        "data" => $formatarData($dados["data"] ?? null),
        "evento" => $formatarEvento($dados["evento"] ?? null),
        "nome_usuario" => $dados["nome_usuario"] ?? null,
        "entidade" => $dados["entidade"] ?? null,
        "registro_id" => $dados["registro_id"] ?? null,
        "ip" => $dados["ip"] ?? null,
        "mensagem" => $dados["mensagem"] ?? null,
        "status_classe" => $statusClasse,
        "status_texto" => $statusTexto,
        "status_icone" => $statusIcone
    ];
}

$quantidadeLogs = count($registros);
?>
<main class="container-fluid py-3 py-md-4">
    <style>
        .painel-logs-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem
        }

        .painel-logs-cabecalho h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0
        }

        .painel-logs-acoes {
            display: flex;
            gap: .6rem;
            flex-shrink: 0
        }

        .card-logs {
            border: 0;
            border-radius: .8rem;
            overflow: hidden
        }

        .card-logs-header {
            background: #0d6efd;
            color: #fff;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem
        }

        .card-logs-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0
        }

        .quantidade-registros {
            background: rgba(255, 255, 255, .2);
            padding: .2rem .55rem;
            border-radius: 2rem;
            font-size: .75rem;
            white-space: nowrap
        }

        .tabela-scroll {
            max-height: 338px;
            overflow: auto
        }

        .tabela-logs {
            min-width: 1150px;
            margin-bottom: 0
        }

        .tabela-logs thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            height: 48px;
            background: #e9f2ff;
            color: #212529;
            border-bottom: 2px solid #0d6efd;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle
        }

        .tabela-logs th:not(:last-child),
        .tabela-logs td:not(:last-child) {
            border-right: 1px solid #d9e2ef
        }

        .tabela-logs thead th:not(:last-child) {
            border-right-color: #c7d7ee
        }

        .tabela-logs tbody tr {
            height: 58px
        }

        .tabela-logs tbody td {
            background: #fff;
            vertical-align: middle
        }

        .tabela-logs tbody tr:hover td {
            background: #f8fbff
        }

        .evento-log,
        .usuario-log {
            font-weight: 600
        }

        .mensagem-log {
            min-width: 230px;
            max-width: 330px;
            overflow-wrap: anywhere
        }

        .badge-status {
            min-width: 82px
        }

        .estado-vazio {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d
        }

        .estado-vazio i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .75rem
        }

        .cards-mobile {
            display: none
        }

        .cards-mobile-scroll {
            background: #f8f9fa
        }

        .log-mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: .75rem;
            padding: 1rem;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075)
        }

        .log-mobile-card+.log-mobile-card {
            margin-top: .75rem
        }

        .log-mobile-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .9rem
        }

        .log-mobile-evento {
            font-size: .95rem;
            font-weight: 700;
            color: #212529;
            overflow-wrap: anywhere
        }

        .log-mobile-data {
            font-size: .75rem;
            color: #6c757d;
            margin-top: .15rem
        }

        .log-mobile-info {
            display: grid;
            gap: .55rem
        }

        .log-mobile-linha {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: .45rem
        }

        .log-mobile-linha:last-child {
            border-bottom: 0;
            padding-bottom: 0
        }

        .log-mobile-label {
            color: #6c757d;
            font-size: .8rem;
            font-weight: 500;
            flex-shrink: 0
        }

        .log-mobile-valor {
            font-size: .84rem;
            font-weight: 600;
            text-align: right;
            overflow-wrap: anywhere;
            min-width: 0
        }

        .log-mobile-mensagem {
            margin-top: .9rem;
            padding: .75rem;
            border-radius: .6rem;
            background: #f8f9fa;
            font-size: .84rem;
            line-height: 1.45;
            color: #495057;
            overflow-wrap: anywhere
        }

        .tabela-scroll::-webkit-scrollbar,
        .cards-mobile-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px
        }

        .tabela-scroll::-webkit-scrollbar-thumb,
        .cards-mobile-scroll::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px
        }

        .tabela-scroll::-webkit-scrollbar-track,
        .cards-mobile-scroll::-webkit-scrollbar-track {
            background: #f1f3f5
        }

        @media(max-width:767.98px) {
            .painel-logs-cabecalho {
                flex-direction: column;
                align-items: stretch
            }

            .painel-logs-cabecalho h1 {
                font-size: 1.35rem
            }

            .painel-logs-acoes {
                display: grid;
                grid-template-columns: 1fr
            }

            .painel-logs-acoes .btn {
                width: 100%
            }

            .tabela-desktop {
                display: none
            }

            .cards-mobile {
                display: block
            }

            .cards-mobile-scroll {
                max-height: 620px;
                overflow-y: auto;
                padding: .75rem
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="painel-logs-cabecalho">
                <div>
                    <h1><i class="bi bi-journal-text me-2 text-primary"></i>Logs do Sistema</h1>
                    <div class="text-body-secondary mt-1">
                        Consulte as principais operações registradas no portal.
                    </div>
                </div>
                <div class="painel-logs-acoes">
                    <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>
            </div>

            <div class="card shadow-sm card-logs">
                <div class="card-logs-header">
                    <h2><i class="bi bi-clock-history me-2"></i>Registros de atividade</h2>
                    <span class="quantidade-registros">
                        <?= $quantidadeLogs ?> <?= $quantidadeLogs === 1 ? "registro" : "registros" ?>
                    </span>
                </div>

                <?php if ($quantidadeLogs === 0): ?>
                    <div class="estado-vazio">
                        <i class="bi bi-journal-x"></i>
                        <strong>Nenhum log encontrado</strong>
                        <div class="small mt-1">Ainda não existem registros de atividade para exibir.</div>
                    </div>
                <?php else: ?>

                    <div class="tabela-scroll tabela-desktop">
                        <table class="table table-hover tabela-logs">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Evento</th>
                                    <th>Usuário</th>
                                    <th>Alvo</th>
                                    <th>IP</th>
                                    <th class="text-center">Status</th>
                                    <th>Mensagem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros as $log): ?>
                                    <tr>
                                        <td class="text-nowrap">
                                            <i class="bi bi-calendar3 text-primary me-1"></i>
                                            <?= $exibirValor($log["data"]) ?>
                                        </td>
                                        <td>
                                            <div class="evento-log">
                                                <i class="bi bi-activity text-primary me-1"></i>
                                                <?= $exibirValor($log["evento"]) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="usuario-log">
                                                <i class="bi bi-person-circle text-primary me-1"></i>
                                                <?= $exibirValor($log["nome_usuario"], "Sistema") ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= $exibirValor($log["entidade"]) ?>
                                            <?php if ($log["registro_id"] !== null && $log["registro_id"] !== ""): ?>
                                                <span class="text-body-secondary">
                                                    #<?= (int)$log["registro_id"] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <?= $exibirValor($log["ip"]) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge text-bg-<?= $log["status_classe"] ?> badge-status">
                                                <i class="bi <?= $log["status_icone"] ?> me-1"></i>
                                                <?= $log["status_texto"] ?>
                                            </span>
                                        </td>
                                        <td class="mensagem-log">
                                            <?= $exibirValor($log["mensagem"]) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cards-mobile">
                        <div class="cards-mobile-scroll">
                            <?php foreach ($registros as $log): ?>
                                <div class="log-mobile-card">
                                    <div class="log-mobile-topo">
                                        <div class="flex-grow-1" style="min-width:0">
                                            <div class="log-mobile-evento">
                                                <?= $exibirValor($log["evento"]) ?>
                                            </div>
                                            <div class="log-mobile-data">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= $exibirValor($log["data"]) ?>
                                            </div>
                                        </div>
                                        <span class="badge text-bg-<?= $log["status_classe"] ?> flex-shrink-0">
                                            <?= $log["status_texto"] ?>
                                        </span>
                                    </div>

                                    <div class="log-mobile-info">
                                        <div class="log-mobile-linha">
                                            <span class="log-mobile-label">Usuário</span>
                                            <span class="log-mobile-valor">
                                                <?= $exibirValor($log["nome_usuario"], "Sistema") ?>
                                            </span>
                                        </div>
                                        <div class="log-mobile-linha">
                                            <span class="log-mobile-label">Alvo</span>
                                            <span class="log-mobile-valor">
                                                <?= $exibirValor($log["entidade"]) ?>
                                                <?php if ($log["registro_id"] !== null && $log["registro_id"] !== ""): ?>
                                                    #<?= (int)$log["registro_id"] ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="log-mobile-linha">
                                            <span class="log-mobile-label">IP</span>
                                            <span class="log-mobile-valor">
                                                <?= $exibirValor($log["ip"]) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="log-mobile-mensagem">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <?= $exibirValor($log["mensagem"]) ?>
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