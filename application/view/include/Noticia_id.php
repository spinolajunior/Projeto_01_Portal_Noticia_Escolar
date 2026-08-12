<?php 
$noticia = $model["noticia"];
$adm = $model["adm"] ?>

<main class="container my-5">
    <div class="row justify-content-center">

        <div class="col-12 col-lg-10 col-xl-9">

            <article class="bg-white rounded shadow-sm p-4 p-md-5">

                <!-- Data -->
                <small class="text-primary fw-semibold">
                    Publicado em
                    <?= date('d/m/Y \à\s H:i', strtotime($noticia->data_pub)) ?>
                </small>

                <!-- Título -->
                <h1 class="display-5 fw-bold mt-2 mb-3">
                    <?= htmlspecialchars($noticia->titulo) ?>
                </h1>

                <!-- Subtítulo -->
                <?php if (!empty($noticia->subtitulo)): ?>

                    <p class="lead text-secondary mb-4">
                        <?= htmlspecialchars($noticia->subtitulo) ?>
                    </p>

                <?php endif; ?>

                <!-- Autor -->
                <p class="text-muted mb-4">
                    <i class="bi bi-person-circle"></i>
                    Publicado por : <?=$adm ->nome ?>
                </p>

                <hr class="mb-4">

                <!-- Imagem -->
                <img src="view/img/upload/<?= htmlspecialchars($noticia->imagem)?>.jpg"
                    class="img-fluid rounded shadow-sm w-100 mb-4"
                    style="max-height:400px; object-fit:cover;">

                <!-- Conteúdo -->
                <div class="fs-5 lh-lg text-justify">

                    <?= nl2br(htmlspecialchars($noticia->descricao)) ?>

                </div>

                <hr class="my-5">

                <!-- Botão -->
                <div class="text-center">

                    <a href="/"
                        class="btn btn-primary px-4 py-2">

                        <i class="bi bi-arrow-left me-2"></i>
                        Voltar para as Notícias

                    </a>

                    

                </div>

            </article>

        </div>

    </div>

</main>