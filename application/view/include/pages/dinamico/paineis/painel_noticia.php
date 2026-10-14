<?php
$noticias = $model["noticias"] ?? [];

if (!is_iterable($noticias)) {
  $noticias = [];
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
    return (new DateTimeImmutable((string)$data))->format("d/m/Y H:i:s");
  } catch (Exception $e) {
    return "Não definida";
  }
};

$listaNoticias = [];

foreach ($noticias as $noticia) {
  if (!is_object($noticia) && !is_array($noticia)) continue;

  $noticia = (object)$noticia;
  $titulo = trim((string)($noticia->titulo ?? ""));
  $autor = trim((string)($noticia->nome_autor ?? ""));
  $status = $noticia->status ?? null;

  if ($status === null || (string)$status === "") {
    $statusTexto = "Indefinido";
    $statusClasse = "secondary";
    $statusIcone = "bi-question-circle";
  } elseif ((int)$status === 1) {
    $statusTexto = "Ativo";
    $statusClasse = "success";
    $statusIcone = "bi-check-circle";
  } elseif ((int)$status === 0) {
    $statusTexto = "Inativo";
    $statusClasse = "danger";
    $statusIcone = "bi-x-circle";
  } else {
    $statusTexto = "Indefinido";
    $statusClasse = "secondary";
    $statusIcone = "bi-question-circle";
  }

  $listaNoticias[] = [
    "id" => (int)($noticia->id ?? 0),
    "titulo" => $titulo !== "" ? $titulo : "Título não informado",
    "autor" => $autor !== "" ? $autor : "Não informado",
    "data" => $formatarData($noticia->data_pub ?? null),
    "data_original" => (string)($noticia->data_pub ?? ""),
    "status" => $statusTexto,
    "classe" => $statusClasse,
    "icone" => $statusIcone
  ];
}

$quantidadeNoticias = count($listaNoticias);
?>

<main class="container-fluid py-3 py-md-4 painel-noticias">
  <style>
    .painel-noticias .painel-cabecalho {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .painel-noticias .painel-cabecalho h1 {
      font-size: 1.6rem;
      font-weight: 700;
      margin: 0;
    }

    .painel-noticias .painel-acoes {
      display: flex;
      align-items: center;
      gap: .6rem;
      flex-shrink: 0;
    }

    .painel-noticias .painel-card {
      border: 0;
      border-radius: .8rem;
      overflow: hidden;
    }

    .painel-noticias .painel-card-header {
      background: #0d6efd;
      color: #fff;
      padding: .9rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
    }

    .painel-noticias .painel-card-header h2 {
      font-size: 1rem;
      font-weight: 600;
      margin: 0;
    }

    .painel-noticias .quantidade-registros {
      background: rgba(255, 255, 255, .2);
      padding: .2rem .55rem;
      border-radius: 2rem;
      font-size: .75rem;
      white-space: nowrap;
    }

    .painel-noticias .barra-busca {
      padding: .85rem 1rem;
      background: #fff;
      border-bottom: 1px solid #edf0f4;
    }

    .painel-noticias .barra-busca .input-group {
      max-width: 440px;
    }

    .painel-noticias .barra-busca .form-control,
    .painel-noticias .barra-busca .input-group-text {
      border-color: #dce3ed;
    }

    .painel-noticias .tabela-scroll {
      max-height: 420px;
      overflow: auto;
    }

    .painel-noticias .tabela-gerenciamento {
      margin-bottom: 0;
      min-width: 900px;
    }

    .painel-noticias .tabela-gerenciamento thead th {
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

    .painel-noticias .tabela-gerenciamento th:not(:last-child),
    .painel-noticias .tabela-gerenciamento td:not(:last-child) {
      border-right: 1px solid #d9e2ef;
    }

    .painel-noticias .tabela-gerenciamento thead th:not(:last-child) {
      border-right-color: #c7d7ee;
    }

    .painel-noticias .tabela-gerenciamento tbody tr {
      height: 58px;
    }

    .painel-noticias .tabela-gerenciamento tbody td {
      background: #fff;
      vertical-align: middle;
    }

    .painel-noticias .tabela-gerenciamento tbody tr:hover td {
      background: #f8fbff;
    }

    .painel-noticias .titulo-noticia-desktop {
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .painel-noticias .status-noticia {
      display: inline-block;
      min-width: 85px;
      padding: .4rem .6rem;
      font-size: .75rem;
      font-weight: 600;
    }

    .painel-noticias .acoes-tabela {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .45rem;
      white-space: nowrap;
    }

    .painel-noticias .acao-tabela {
      border-radius: .4rem;
      transition: transform .18s;
    }

    .painel-noticias .acao-tabela:hover {
      transform: translateY(-1px);
    }

    .painel-noticias .cards-mobile {
      display: none;
      background: #f8fafc;
    }

    .painel-noticias .cards-mobile-lista {
      display: grid;
      grid-template-columns: 1fr;
      gap: .75rem;
      padding: .85rem;
    }

    .painel-noticias .card-noticia-mobile {
      display: flex;
      flex-direction: column;
      min-width: 0;
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
      padding: 1rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
    }

    .painel-noticias .noticia-mobile-topo {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .65rem;
      margin-bottom: .8rem;
    }

    .painel-noticias .noticia-mobile-identificacao {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .8rem;
      font-weight: 600;
      color: #64748b;
    }

    .painel-noticias .noticia-mobile-icone {
      width: 30px;
      height: 30px;
      min-width: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #eaf2ff;
      color: #0d6efd;
      border-radius: 8px;
    }

    .painel-noticias .titulo-noticia-mobile {
      font-size: .96rem;
      line-height: 1.5;
      font-weight: 700;
      color: #263345;
      overflow-wrap: anywhere;
      margin-bottom: .85rem;
    }

    .painel-noticias .noticia-mobile-info {
      display: grid;
      gap: .5rem;
      margin-bottom: .9rem;
    }

    .painel-noticias .noticia-mobile-linha {
      display: flex;
      align-items: flex-start;
      gap: .55rem;
      min-width: 0;
      font-size: .83rem;
      color: #64748b;
    }

    .painel-noticias .noticia-mobile-linha i {
      color: #0d6efd;
      flex-shrink: 0;
    }

    .painel-noticias .noticia-mobile-valor {
      color: #334155;
      overflow-wrap: anywhere;
      min-width: 0;
    }

    .painel-noticias .acoes-mobile {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .55rem;
      border-top: 1px solid #edf0f4;
      padding-top: .85rem;
      margin-top: auto;
    }

    .painel-noticias .acoes-mobile .btn {
      width: 100%;
      min-height: 39px;
      border-radius: 7px;
      font-size: .85rem;
      font-weight: 600;
    }

    .painel-noticias .mobile-lista-rodape {
      padding: .4rem .85rem 1rem;
      text-align: center;
    }

    .painel-noticias .mobile-lista-contador {
      font-size: .78rem;
      color: #6c757d;
      margin-bottom: .65rem;
    }

    .painel-noticias .mobile-mostrar-mais {
      border-radius: 8px;
      font-size: .85rem;
      font-weight: 600;
      padding: .55rem 1.25rem;
    }

    .painel-noticias .estado-vazio {
      text-align: center;
      padding: 3rem 1rem;
      color: #6c757d;
    }

    .painel-noticias .estado-vazio i {
      display: block;
      font-size: 2.5rem;
      margin-bottom: .75rem;
    }

    .painel-noticias .sem-resultados {
      padding: 2.5rem 1rem;
      text-align: center;
      color: #6c757d;
    }

    .painel-noticias [hidden] {
      display: none !important;
    }

    .painel-noticias .tabela-scroll::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    .painel-noticias .tabela-scroll::-webkit-scrollbar-thumb {
      background: #adb5bd;
      border-radius: 10px;
    }

    .painel-noticias .tabela-scroll::-webkit-scrollbar-track {
      background: #f1f3f5;
    }

    @media(min-width:700px) and (max-width:991.98px) {
      .painel-noticias .cards-mobile-lista {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media(max-width:991.98px) {
      .painel-noticias .tabela-desktop {
        display: none;
      }

      .painel-noticias .cards-mobile {
        display: block;
      }
    }

    @media(max-width:767.98px) {
      .painel-noticias .painel-cabecalho {
        flex-direction: column;
        align-items: stretch;
      }

      .painel-noticias .painel-cabecalho h1 {
        font-size: 1.35rem;
      }

      .painel-noticias .painel-acoes {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }

      .painel-noticias .painel-acoes .btn {
        width: 100%;
      }

      .painel-noticias .barra-busca .input-group {
        max-width: none;
      }
    }

    @media(max-width:575.98px) {
      .painel-noticias .painel-acoes {
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
          <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Voltar
          </a>

          <a href="/cadastro/noticia" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Nova Notícia
          </a>
        </div>
      </div>

      <section class="card shadow-sm painel-card" data-painel-noticias>

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
            <i class="bi bi-journal-x"></i>
            <strong>Nenhuma notícia cadastrada</strong>
            <div class="small mt-1">
              Cadastre uma notícia para começar a gerenciar as publicações.
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
                placeholder="Pesquisar por ID, título, autor ou status..."
                aria-label="Pesquisar notícias"
                autocomplete="off"
                data-busca-noticias>
            </div>
          </div>

          <div class="tabela-scroll tabela-desktop">
            <table class="table table-hover tabela-gerenciamento">
              <thead>
                <tr>
                  <th class="ps-3 ps-md-4">ID</th>
                  <th>Título</th>
                  <th>Publicado por</th>
                  <th>Data da publicação</th>
                  <th class="text-center">Status</th>
                  <th class="text-center pe-3 pe-md-4">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($listaNoticias as $noticia): ?>
                  <?php
                  $busca = implode(" ", [
                    $noticia["id"],
                    $noticia["titulo"],
                    $noticia["autor"],
                    $noticia["data"],
                    $noticia["data_original"],
                    $noticia["status"]
                  ]);
                  ?>

                  <tr
                    data-linha-noticia
                    data-busca="<?= $exibirValor($busca) ?>">

                    <td class="ps-3 ps-md-4">
                      <?= $noticia["id"] ?>
                    </td>

                    <td style="min-width:180px">
                      <div class="titulo-noticia-desktop">
                        <i class="bi bi-newspaper text-primary me-1"></i>
                        <?= $exibirValor($noticia["titulo"]) ?>
                      </div>
                    </td>

                    <td>
                      <i class="bi bi-person-circle text-primary me-1"></i>
                      <?= $exibirValor($noticia["autor"]) ?>
                    </td>

                    <td class="text-nowrap">
                      <i class="bi bi-calendar3 text-primary me-1"></i>
                      <?= $exibirValor($noticia["data"]) ?>
                    </td>

                    <td class="text-center">
                      <span class="badge text-bg-<?= $noticia["classe"] ?> status-noticia">
                        <i class="bi <?= $noticia["icone"] ?> me-1"></i>
                        <?= $noticia["status"] ?>
                      </span>
                    </td>

                    <td class="pe-3 pe-md-4">
                      <div class="acoes-tabela">
                        <a
                          href="/atualizar/noticia?id=<?= $noticia["id"] ?>"
                          class="btn btn-warning btn-sm acao-tabela"
                          title="Editar notícia">
                          <i class="bi bi-pencil-square me-1"></i>
                          Editar
                        </a>

                        <a
                          href="/excluir/noticia?id=<?= $noticia["id"] ?>"
                          class="btn btn-danger btn-sm acao-tabela"
                          title="Excluir notícia"
                          data-confirmar-exclusao
                          data-titulo="<?= $exibirValor($noticia["titulo"]) ?>">
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

              <?php foreach ($listaNoticias as $noticia): ?>
                <?php
                $busca = implode(" ", [
                  $noticia["id"],
                  $noticia["titulo"],
                  $noticia["autor"],
                  $noticia["data"],
                  $noticia["data_original"],
                  $noticia["status"]
                ]);
                ?>

                <article
                  class="card-noticia-mobile"
                  data-card-noticia
                  data-busca="<?= $exibirValor($busca) ?>">

                  <div class="noticia-mobile-topo">
                    <div class="noticia-mobile-identificacao">
                      <div class="noticia-mobile-icone">
                        <i class="bi bi-newspaper"></i>
                      </div>

                      Notícia #<?= $noticia["id"] ?>
                    </div>

                    <span class="badge text-bg-<?= $noticia["classe"] ?> status-noticia">
                      <?= $noticia["status"] ?>
                    </span>
                  </div>

                  <h3 class="titulo-noticia-mobile">
                    <?= $exibirValor($noticia["titulo"]) ?>
                  </h3>

                  <div class="noticia-mobile-info">
                    <div class="noticia-mobile-linha">
                      <i class="bi bi-person-circle"></i>
                      <div class="noticia-mobile-valor">
                        <span class="text-secondary">Por:</span>
                        <?= $exibirValor($noticia["autor"]) ?>
                      </div>
                    </div>

                    <div class="noticia-mobile-linha">
                      <i class="bi bi-calendar3"></i>
                      <div class="noticia-mobile-valor">
                        <?= $exibirValor($noticia["data"]) ?>
                      </div>
                    </div>
                  </div>

                  <div class="acoes-mobile">
                    <a
                      href="/atualizar/noticia?id=<?= $noticia["id"] ?>"
                      class="btn btn-warning">
                      <i class="bi bi-pencil-square me-1"></i>
                      Editar
                    </a>

                    <a
                      href="/excluir/noticia?id=<?= $noticia["id"] ?>"
                      class="btn btn-danger"
                      data-confirmar-exclusao
                      data-titulo="<?= $exibirValor($noticia["titulo"]) ?>">
                      <i class="bi bi-trash-fill me-1"></i>
                      Excluir
                    </a>
                  </div>
                </article>

              <?php endforeach; ?>

            </div>

            <div class="mobile-lista-rodape" data-rodape-mobile>
              <div
                class="mobile-lista-contador"
                data-contador-mobile
                aria-live="polite"></div>

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
            Nenhuma notícia corresponde à pesquisa.
          </div>

        <?php endif; ?>

      </section>
    </div>
  </div>
</main>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const painel = document.querySelector(".painel-noticias [data-painel-noticias]");
    if (!painel) return;

    const normalizar = texto => String(texto || "")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .trim();

    const busca = painel.querySelector("[data-busca-noticias]");
    const linhas = [...painel.querySelectorAll("[data-linha-noticia]")];
    const cards = [...painel.querySelectorAll("[data-card-noticia]")];
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
          "Exibindo " + exibidos + " de " + encontrados + " notícias";
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

        const titulo = link.dataset.titulo || "esta notícia";

        const confirmar = confirm(
          'Deseja realmente excluir a notícia "' +
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

    window.addEventListener("pageshow", event => {
      if (!event.persisted) return;

      painel.querySelectorAll("[data-confirmar-exclusao]").forEach(link => {
        delete link.dataset.enviando;
      });
    });

    atualizarLista();
  });
</script>