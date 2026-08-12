<main class="container my-4">

    <section class="mb-5">

        <h2 class="text-center fw-bold mb-3">
            DESTAQUES DA SEMANA
        </h2>

        <div id="carouselNoticias"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel"
            data-bs-interval="10000">


            <div class="carousel-indicators">

                <?php foreach ($model["destaques"] as $index => $noticia): ?>

                    <button type="button"
                        data-bs-target="#carouselNoticias"
                        data-bs-slide-to="<?= $index ?>"
                        class="<?= $index == 0 ? 'active' : '' ?>">
                    </button>

                <?php endforeach; ?>

            </div>



            <div class="carousel-inner rounded">

                <?php foreach ($model["destaques"] as $index => $noticia): ?>

                    <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">

                        <img src="view/img/upload/<?= htmlspecialchars($noticia->imagem)?>.jpg"
                            class="d-block w-100"
                            style="height:420px; object-fit:cover;">


                        <div class="carousel-caption text-start">

                            <small>
                                Publicado em:
                                <?= date('d/m/Y', strtotime($noticia->data_pub)) ?>
                            </small>

                            <h2>
                                <?= htmlspecialchars($noticia->titulo) ?>
                            </h2>

                            <p>
                                <?= mb_substr(
                                    strip_tags($noticia->descricao),
                                    0,
                                    90,
                                    'UTF-8'
                                ) ?>...
                            </p>

                            <a href="/noticia?id=<?= $noticia->id ?>"
                                class="btn btn-primary">
                                Leia Mais
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>



            <button class="carousel-control-prev"
                type="button"
                data-bs-target="#carouselNoticias"
                data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>

            </button>


            <button class="carousel-control-next"
                type="button"
                data-bs-target="#carouselNoticias"
                data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>

            </button>


        </div>

    </section>



    <section>

        <div class="row">


            <div class="col-lg-9">


                <h2 class="fw-bold mb-3">
                    ÚLTIMAS NOTÍCIAS
                </h2>


                <div class="row g-4">


                    <?php foreach ($model["noticias"] as $noticia): ?>


                        <div class="col-md-6 col-xl-4">

                            <div class="card h-100 shadow-sm">


                                <img src="view/img/upload/<?= htmlspecialchars($noticia->imagem)?>.jpg"
                                    class="card-img-top"
                                    style="height:180px; object-fit:cover;">



                                <div class="card-body">


                                    <small class="text-primary">
                                        Publicado em:
                                        <?= date('d/m/Y', strtotime($noticia->data_pub)) ?>
                                    </small>


                                    <h5 class="card-title mt-2">
                                        <?= htmlspecialchars($noticia->titulo) ?>
                                    </h5>


                                    <p class="card-text">
                                        <?= mb_substr(
                                            strip_tags($noticia->descricao),
                                            0,
                                            120,
                                            'UTF-8'
                                        ) ?>...
                                    </p>


                                </div>



                                <div class="card-footer bg-white border-0">

                                    <a href="/noticia?id=<?= $noticia->id ?>"
                                        class="btn btn-primary btn-sm">

                                        Leia Mais

                                    </a>

                                </div>



                            </div>

                        </div>


                    <?php endforeach; ?>


                </div>



                <nav class="mt-4">

                    <ul class="pagination justify-content-center">


                        <li class="page-item <?= $model["pagina"] <= 1 ? 'disabled' : '' ?>">

                            <a class="page-link"
                                href="?pagina=<?= $model["pagina"] - 1 ?>">

                                &laquo;

                            </a>

                        </li>



                        <?php for ($i = 1; $i <= $model["totalPaginas"]; $i++): ?>


                            <li class="page-item <?= $i == $model["pagina"] ? 'active' : '' ?>">

                                <a class="page-link"
                                    href="?pagina=<?= $i ?>">

                                    <?= $i ?>

                                </a>

                            </li>


                        <?php endfor; ?>



                        <li class="page-item <?= $model["pagina"] >= $model["totalPaginas"] ? 'disabled' : '' ?>">

                            <a class="page-link"
                                href="?pagina=<?= $model["pagina"] + 1 ?>">

                                &raquo;

                            </a>

                        </li>


                    </ul>

                </nav>


            </div>



            <aside class="col-lg-3">


                <div class="card shadow-sm mb-4">

                    <div class="card-header fw-bold">
                        Agenda Escolar
                    </div>


                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            17 Ago - Início das provas
                        </li>

                        <li class="list-group-item">
                            20 Ago - Reunião de Pais
                        </li>

                        <li class="list-group-item">
                            25 Ago - Feira Cultural
                        </li>

                    </ul>

                </div>



                <div class="card shadow-sm">

                    <div class="card-header fw-bold">
                        Comunicados
                    </div>


                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            Uniforme obrigatório
                        </li>

                        <li class="list-group-item">
                            Vagas para monitoria
                        </li>

                    </ul>

                </div>


            </aside>


        </div>

    </section>


</main>