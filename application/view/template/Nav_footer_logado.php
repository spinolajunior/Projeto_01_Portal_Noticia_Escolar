<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link rel="icon" href="../view/img/logo_estado_da_bahia.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-body-secondary d-flex flex-column min-vh-100">


    <header class="bg-primary text-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-2">
            <!-- 'container' centraliza e limita a largura no desktop como no modelo da imagem -->
            <div class="container px-5">

                <!-- 1. ESQUERDA: Logo + Nome da Escola -->
                <div class="d-flex align-items-center me-lg-3">
                    <a href="index.php" class="me-2 me-sm-3 d-inline-block flex-shrink-0">
                        <img src="../view/img/logo_estado_da_bahia.png"
                            alt="Logo Colégio Estadual Doutor Aristides Maltez"
                            width="285"
                            height="338"
                            class="img-fluid"
                            style="height: 45px; width: auto; max-height: 55px;">
                    </a>

                    <div class="lh-sm">
                        <h1 class="mb-0 fw-bold text-uppercase text-white text-wrap" style="font-size: clamp(0.8rem, 1.1vw, 1rem);">
                            Colégio Estadual Doutor Aristides Maltez
                        </h1>
                        <small class="text-white-50 text-uppercase d-block" style="font-size: clamp(0.6rem, 0.8vw, 0.75rem);">
                            Ministério da Educação
                        </small>
                    </div>
                </div>

                <!-- 2. BOTÃO TOGGLER (Apenas Mobile) -->
                <button class="navbar-toggler btn-sm py-1 px-3 border-white border-opacity-50 mx-auto my-2 w-100 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-4 text-white align-middle"></i>
                </button>

                <!-- 3. CENTRO E DIREITA: Menu e Ações -->
                <div class="collapse navbar-collapse" id="navbarNav">

                    <!-- Links: Centralizados no Desktop e no Mobile -->
                    <ul class="navbar-nav mx-auto mb-3 mb-lg-0 text-center align-items-center justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold px-lg-2 px-xl-3" aria-current="page" href="/">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-lg-2 px-xl-3" href="#">Notícias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-lg-2 px-xl-3" href="#">Eventos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-lg-2 px-xl-3" href="#">Contato</a>
                        </li>
                    </ul>

                    <!-- Direita: Botão Entrar -->
                    <div class="d-flex justify-content-center justify-content-lg-end pt-2 pt-lg-0 border-top border-white border-opacity-25 border-lg-0">
                        <a href="/login" class="btn btn-light text-primary fw-bold btn-sm px-3 py-1 shadow-sm d-flex align-items-center gap-1 ms-lg-2">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Entrar</span>
                        </a>
                    </div>

                </div>

            </div>
        </nav>
    </header>

        <?= $content ?>

    <footer class="bg-dark text-white text-center py-1 mt-auto">
        <div class="container small opacity-75">
            <p class="mb-1">Colégio Estadual de Tempo Integral Doutor Aristides Maltez - &copy; 2026. Todos os direitos reservados.</p>
            <p class="mb-1">Rua Nova do Areal, s/n, Centro, Jaguaripe - BA.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para alternar visualização da senha -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            eyeIcon.classList.toggle('bi-eye-fill');
            eyeIcon.classList.toggle('bi-eye-slash-fill');
        });
    </script>
</body>

</html>