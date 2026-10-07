<?php

$noticias = $model["noticias"] ?? [];

if (!is_iterable($noticias)) {
  $noticias = [];
}

$quantidadeNoticias = is_countable($noticias)
  ? count($noticias)
  : 0;

$formatarData = static function ($data): string {
  if (empty($data)) {
    return "Não definida";
  }

  try {
    return (new DateTimeImmutable($data))->format("d/m/Y H:i:s");
  } catch (Exception $e) {
    return "Não definida";
  }
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

    .painel-card {
      border: 0;
      border-radius: .8rem;
      overflow: hidden;
    }

    .painel-card-header {
      background-color: #0d6efd;
      color: #fff;
      padding: .9rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
    }

    .painel-card-header h2 {
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

    .tabela-gerenciamento {
      margin-bottom: 0;
    }

    .tabela-gerenciamento thead th {
      background-color: #e9f2ff;
      color: #212529;
      font-weight: 600;
      border-bottom: 2px solid #0d6efd;
      white-space: nowrap;
      vertical-align: middle;
      height: 48px;
    }

    .tabela-gerenciamento th:not(:last-child),
    .tabela-gerenciamento td:not(:last-child) {
      border-right: 1px solid #d9e2ef;
    }

    .tabela-gerenciamento thead th:not(:last-child) {
      border-right: 1px solid #c7d7ee;
    }

    .tabela-gerenciamento tbody tr {
      height: 58px;
      transition: background-color .18s ease;
    }

    .tabela-gerenciamento tbody td {
      background-color: #fff;
      vertical-align: middle;
    }

    .tabela-gerenciamento tbody tr:hover td {
      background-color: #f8fbff;
    }

    .titulo-noticia-desktop {
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .status-noticia {
      font-size: .75rem;
      font-weight: 600;
      min-width: 85px;
      display: inline-block;
    }

    .acoes-tabela {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: .45rem;
      white-space: nowrap;
    }

    .acao-tabela {
      border-radius: .4rem;
      transition: transform .18s ease;
    }

    .acao-tabela:hover {
      transform: translateY(-1px);
    }

    .card-noticia-mobile {
      border: 1px solid #dee2e6;
      border-radius: .8rem;
      overflow: hidden;
      background-color: #fff;
    }

    .card-noticia-mobile .card-header {
      background-color: #0d6efd;
      color: #fff;
      border: 0;
      padding: .9rem 1rem;
    }

    .card-noticia-mobile .titulo-noticia {
      font-size: 1rem;
      font-weight: 600;
      line-height: 1.5;
      overflow-wrap: anywhere;
    }

    .card-noticia-mobile .info-label {
      color: #6c757d;
      font-size: .78rem;
      margin-bottom: .15rem;
    }

    .card-noticia-mobile .info-valor {
      font-size: .9rem;
      overflow-wrap: anywhere;
    }

    .card-noticia-mobile .icone-info {
      width: 22px;
      flex-shrink: 0;
      color: #0d6efd;
    }

    .acoes-mobile {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .5rem;
    }

    .acoes-mobile .btn {
      width: 100%;
    }

    .estado-vazio {
      padding: 3rem 1rem;
      text-align: center;
      color: #6c757d;
    }

    .estado-vazio i {
      display: block;
      font-size: 2.5rem;
      margin-bottom: .75rem;
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
            <i class="bi bi-newspaper text-primary me-2"></i>
            Notícias
          </h1>

          <div class="text-body-secondary mt-1">
            Gerencie as notícias publicadas no portal.
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
            href="/cadastro/noticia"
            class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Nova Notícia
          </a>

        </div>

      </div>

      <div class="card shadow-sm painel-card">

        <div class="painel-card-header">

          <h2>
            <i class="bi bi-newspaper me-2"></i>
            Notícias cadastradas
          </h2>

          <span class="quantidade-registros">
            <?= $quantidadeNoticias ?>
            <?= $quantidadeNoticias === 1 ? "registro" : "registros" ?>
          </span>

        </div>

        <?php if ($quantidadeNoticias === 0): ?>

          <div class="estado-vazio">

            <i class="bi bi-newspaper"></i>

            <strong>
              Nenhuma notícia cadastrada
            </strong>

            <div class="small mt-1">
              Cadastre uma notícia para começar a gerenciar as publicações.
            </div>

          </div>

        <?php else: ?>

          <div class="d-none d-lg-block">

            <div class="table-responsive">

              <table class="table table-hover tabela-gerenciamento">

                <thead>
                  <tr>
                    <th class="ps-3 ps-md-4">
                      ID
                    </th>

                    <th>
                      Título
                    </th>

                    <th>
                      Publicado por
                    </th>

                    <th>
                      Data da publicação
                    </th>

                    <th class="text-center">
                      Status
                    </th>

                    <th class="text-center pe-3 pe-md-4">
                      Ações
                    </th>
                  </tr>
                </thead>

                <tbody>

                  <?php foreach ($noticias as $noticia): ?>

                    <?php
                    $dataFormatada = $formatarData(
                      $noticia->data_pub
                    );
                    ?>

                    <tr>

                      <td class="ps-3 ps-md-4">
                        <?= (int) $noticia->id ?>
                      </td>

                      <td style="min-width: 180px;">

                        <div class="titulo-noticia-desktop">

                          <?= htmlspecialchars(
                            $noticia->titulo,
                            ENT_QUOTES,
                            "UTF-8"
                          ) ?>

                        </div>

                      </td>

                      <td>

                        <i class="bi bi-person text-primary me-1"></i>

                        <?= htmlspecialchars(
                          $noticia->nome_autor
                            ?? "Não informado",
                          ENT_QUOTES,
                          "UTF-8"
                        ) ?>

                      </td>

                      <td class="text-nowrap">

                        <i class="bi bi-calendar3 text-primary me-1"></i>

                        <?= $dataFormatada ?>

                      </td>

                      <td class="text-center">

                        <?php if ($noticia->status === null): ?>

                          <span class="badge text-bg-secondary status-noticia">
                            Indefinido
                          </span>

                        <?php elseif ((int) $noticia->status === 1): ?>

                          <span class="badge text-bg-success status-noticia">

                            <i class="bi bi-check-circle me-1"></i>
                            Ativo

                          </span>

                        <?php else: ?>

                          <span class="badge text-bg-danger status-noticia">

                            <i class="bi bi-x-circle me-1"></i>
                            Inativo

                          </span>

                        <?php endif; ?>

                      </td>

                      <td class="pe-3 pe-md-4">

                        <div class="acoes-tabela">

                          <a
                            href="/atualizar/noticia?id=<?= (int) $noticia->id ?>"
                            class="btn btn-warning btn-sm acao-tabela">
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar
                          </a>

                          <a
                            href="/excluir/noticia?id=<?= (int) $noticia->id ?>"
                            class="btn btn-danger btn-sm acao-tabela"
                            onclick="return confirm('Deseja realmente excluir esta notícia?');">
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

          </div>

          <div class="d-lg-none p-3">

            <div class="row g-3">

              <?php foreach ($noticias as $noticia): ?>

                <?php
                $dataFormatada = $formatarData(
                  $noticia->data_pub
                );
                ?>

                <div class="col-12 col-md-6">

                  <article class="card card-noticia-mobile h-100 shadow-sm">

                    <div class="card-header">

                      <div class="d-flex justify-content-between align-items-center gap-2">

                        <span class="fw-semibold">

                          <i class="bi bi-newspaper me-1"></i>

                          Notícia #<?= (int) $noticia->id ?>

                        </span>

                        <?php if ($noticia->status === null): ?>

                          <span class="badge text-bg-secondary status-noticia">
                            Indefinido
                          </span>

                        <?php elseif ((int) $noticia->status === 1): ?>

                          <span class="badge text-bg-success status-noticia">

                            <i class="bi bi-check-circle me-1"></i>
                            Ativo

                          </span>

                        <?php else: ?>

                          <span class="badge text-bg-danger status-noticia">

                            <i class="bi bi-x-circle me-1"></i>
                            Inativo

                          </span>

                        <?php endif; ?>

                      </div>

                    </div>

                    <div class="card-body d-flex flex-column">

                      <h2 class="titulo-noticia h6 mb-3">

                        <?= htmlspecialchars(
                          $noticia->titulo,
                          ENT_QUOTES,
                          "UTF-8"
                        ) ?>

                      </h2>

                      <div class="d-flex align-items-start gap-2 mb-3">

                        <i class="bi bi-person-circle icone-info fs-5"></i>

                        <div class="flex-grow-1" style="min-width: 0;">

                          <div class="info-label">
                            Publicado por
                          </div>

                          <div class="info-valor fw-medium">

                            <?= htmlspecialchars(
                              $noticia->nome_autor
                                ?? "Não informado",
                              ENT_QUOTES,
                              "UTF-8"
                            ) ?>

                          </div>

                        </div>

                      </div>

                      <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-calendar3 icone-info fs-5"></i>

                        <div class="flex-grow-1">

                          <div class="info-label">
                            Data da publicação
                          </div>

                          <div class="info-valor">
                            <?= $dataFormatada ?>
                          </div>

                        </div>

                      </div>

                      <div class="mt-auto pt-3 border-top">

                        <div class="acoes-mobile">

                          <a
                            href="/atualizar/noticia?id=<?= (int) $noticia->id ?>"
                            class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar
                          </a>

                          <a
                            href="/excluir/noticia?id=<?= (int) $noticia->id ?>"
                            class="btn btn-danger"
                            onclick="return confirm('Deseja realmente excluir esta notícia?');">
                            <i class="bi bi-trash-fill me-1"></i>
                            Excluir
                          </a>

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
  </div>

</main>

<script>
  function voltarPainel(event) {
    if (window.history.length > 1) {
      event.preventDefault();
      window.history.back();
      return false;
    }

    return true;
  }
</script>