<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="d-flex flex-column min-vh-100">


    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    <a class="nav-link" href="#">Features</a>
                    <a class="nav-link" href="#">Pricing</a>
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        <h1>Pagina Principal</h1>
    </main>

    

    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row gy-4">
                <div class="col-md-5">
                    <h5 class="fw-bold">Portal de Notícias Escolar</h5>
                    <p class="mb-0 text-white-50">
                        Informação, educação e acontecimentos da nossa comunidade escolar.
                    </p>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">Links rápidos</h6>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white-50 text-decoration-none">Início</a></li>
                        <li><a href="/noticias" class="text-white-50 text-decoration-none">Notícias</a></li>
                        <li><a href="/sobre" class="text-white-50 text-decoration-none">Sobre nós</a></li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">Contato</h6>
                    <p class="mb-1 text-white-50">✉ contato@escola.com</p>
                    <p class="mb-0 text-white-50">☎ (00) 0000-0000</p>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="text-center text-white-50 small">
                © <?= date('Y') ?> Portal de Notícias Escolar. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>

</html>