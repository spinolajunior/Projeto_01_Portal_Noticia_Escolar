<?php

$secao = (object)$_SESSION;

// Verifica a autenticação uma única vez
$logado = \controller\LoginController::logado();

// Nome exibido no menu do usuário
$nomeUsuario = htmlspecialchars(
    (string) ($_SESSION['nome'] ?? 'Minha conta'),
    ENT_QUOTES,
    'UTF-8'
);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>
    </title>

    <!-- FAVICON -->
    <link rel="icon"
        href="/view/img/logo_estado_da_bahia.png"
        type="image/png">

    <!-- BOOTSTRAP 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>
        /* ========================================
           IDENTIDADE VISUAL
        ======================================== */

        .portal-logo {
            height: 45px;
            width: auto;
            max-height: 55px;
            flex-shrink: 0;
        }

        .portal-brand {
            min-width: 0;
        }

        .portal-title {
            font-size: clamp(0.75rem, 1.1vw, 1rem);
            line-height: 1.2;
            overflow-wrap: break-word;
        }

        .portal-subtitle {
            font-size: clamp(0.6rem, 0.8vw, 0.75rem);
        }


        /* ========================================
           MENU LATERAL
        ======================================== */

        .portal-offcanvas {
            --bs-offcanvas-width: min(88vw, 360px);
        }

        .portal-offcanvas .nav-link {
            color: #495057;
            border-radius: 10px;
            padding: 12px 15px;

            transition:
                background-color 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .portal-offcanvas .nav-link:hover {
            background-color: #e9f2ff;
            color: #0d6efd;
            transform: translateX(3px);
        }

        .portal-offcanvas .nav-link i {
            display: inline-block;
            width: 25px;
        }


        /* ========================================
           TÍTULOS DAS SEÇÕES
        ======================================== */

        .menu-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #6c757d;
        }


        /* ========================================
           CARTÃO DO USUÁRIO
        ======================================== */

        .user-profile-card {
            background: #f1f6ff;
            border: 1px solid #dce9ff;
            border-radius: 12px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;

            border-radius: 50%;

            background: #0d6efd;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        /* ========================================
           DROPDOWN DESKTOP
        ======================================== */

        .navbar .dropdown-menu {
            z-index: 1050;
        }


        /* ========================================
           TABLET E MOBILE
        ======================================== */

        @media (max-width: 991.98px) {

            .portal-brand {
                flex: 1;
                min-width: 0;
                margin-right: 0;
            }

            .navbar-toggler {
                flex-shrink: 0;
            }

            .portal-title {
                overflow-wrap: break-word;
            }

        }


        /* ========================================
           CELULARES PEQUENOS
        ======================================== */

        @media (max-width: 575.98px) {

            .portal-logo {
                height: 38px;
            }

            .portal-title {
                font-size: 0.72rem;
            }

            .portal-subtitle {
                font-size: 0.58rem;
            }

        }
    </style>

</head>


<body class="bg-body-secondary d-flex flex-column min-vh-100">

    <header class="bg-primary text-white shadow-sm">

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-2">

            <div class="container px-3 px-sm-4 px-lg-5">


                <a href="/"
                    class="navbar-brand portal-brand d-flex align-items-center gap-2 me-0 order-2 order-lg-0">

                    <img
                        src="/view/img/logo_estado_da_bahia.png"
                        alt="Logo do Colégio Estadual Doutor Aristides Maltez"
                        class="portal-logo">

                    <div class="lh-sm">

                        <h1 class="portal-title mb-0 fw-bold text-uppercase text-white">

                            Colégio Estadual Doutor Aristides Maltez

                        </h1>

                        <small class="portal-subtitle text-white-50 text-uppercase d-block">

                            Ministério da Educação

                        </small>

                    </div>

                </a>




                <!-- BOTÃO MOBILE / TABLET -->


                <div class="d-flex d-lg-none align-items-center order-1 me-2">

                    <button
                        class="navbar-toggler border-white border-opacity-50 px-2 py-1"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#menuMobile"
                        aria-controls="menuMobile"
                        aria-expanded="false"
                        aria-label="Abrir menu lateral">

                        <i class="bi bi-list fs-4 text-white"></i>

                    </button>

                </div>



                <!-- MENU DESKTOP -->

                <div class="d-none d-lg-flex align-items-center flex-grow-1">


                    <!-- NAVEGAÇÃO PRINCIPAL -->

                    <ul class="navbar-nav mx-auto align-items-center">


                        <li class="nav-item">

                            <a class="nav-link fw-semibold px-lg-2 px-xl-3"
                                href="/">

                                <i class="bi bi-house-door me-1"></i>

                                Início

                            </a>

                        </li>


                        <li class="nav-item">

                            <a class="nav-link px-lg-2 px-xl-3"
                                href="/painel/noticia_evento_aviso">

                                <i class="bi bi-newspaper me-1"></i>

                                Notícias

                            </a>

                        </li>


                        <li class="nav-item">

                            <a class="nav-link px-lg-2 px-xl-3"
                                href="#">

                                <i class="bi bi-calendar-event me-1"></i>

                                Eventos

                            </a>

                        </li>


                        <li class="nav-item">

                            <a class="nav-link px-lg-2 px-xl-3"
                                href="#">

                                <i class="bi bi-envelope me-1"></i>

                                Contato

                            </a>

                        </li>


                    </ul>



                    <!-- AUTENTICAÇÃO DESKTOP -->

                    <?php if ($logado): ?>


                        <!-- USUÁRIO AUTENTICADO -->

                        <div class="dropdown">


                            <button
                                class="btn btn-light text-primary fw-bold btn-sm px-3 py-1 shadow-sm d-flex align-items-center gap-2 dropdown-toggle"
                                type="button"
                                id="dropdownUserDesktop"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <i class="bi bi-person-circle fs-6"></i>

                                <span>
                                    <?= $nomeUsuario ?>
                                </span>

                            </button>



                            <ul
                                class="dropdown-menu dropdown-menu-end text-start shadow mt-2"
                                aria-labelledby="dropdownUserDesktop">


                                <li>

                                    <a
                                        class="dropdown-item d-flex align-items-center gap-2"
                                        href="/perfil">

                                        <i class="bi bi-person text-primary"></i>

                                        <span>Meu Perfil</span>

                                    </a>

                                </li>

                                <?php if(isset($_SESSION['id_tipo_adm'])):?>

                                <li>

                                    <a
                                        class="dropdown-item d-flex align-items-center gap-2"
                                        href="/painel/noticia_evento_aviso">

                                        <i class="bi bi-megaphone text-primary"></i>

                                        <span>Notícias, Eventos e Avisos</span>

                                    </a>

                                </li>



                                <li>

                                    <a
                                        class="dropdown-item d-flex align-items-center gap-2"
                                        href="/painel/gerenciamento">

                                        <i class="bi bi-gear text-primary"></i>

                                        <span>Área Administrativa</span>

                                    </a>

                                </li>



                                <li>

                                    <hr class="dropdown-divider">

                                </li>
                                <?php endif; ?>



                                <li>

                                    <a
                                        class="dropdown-item text-danger fw-semibold d-flex align-items-center gap-2"
                                        href="/logout">

                                        <i class="bi bi-box-arrow-right"></i>

                                        <span>Sair</span>

                                    </a>

                                </li>


                            </ul>

                        </div>


                    <?php else: ?>


                        <!-- USUÁRIO NÃO AUTENTICADO -->

                        <a
                            href="/login"
                            class="btn btn-light text-primary fw-bold btn-sm px-3 py-1 shadow-sm d-flex align-items-center gap-1">

                            <i class="bi bi-box-arrow-in-right"></i>

                            <span>Entrar</span>

                        </a>


                    <?php endif; ?>


                </div>


            </div>

        </nav>

    </header>




    <!-- MENU LATERAL MOBILE / TABLET -->

    <div
        class="offcanvas offcanvas-start portal-offcanvas d-lg-none"
        tabindex="-1"
        id="menuMobile"
        aria-labelledby="menuMobileLabel">


        <div class="offcanvas-header bg-primary text-white">


            <div>

                <h5
                    class="offcanvas-title fw-bold"
                    id="menuMobileLabel">

                    Portal CETIDAM

                </h5>

                <small class="text-white-50">

                    Menu de navegação

                </small>

            </div>



            <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Fechar menu">

            </button>


        </div>


        <div class="offcanvas-body d-flex flex-column">



            <?php if ($logado): ?>

                <div class="user-profile-card p-3 mb-4">


                    <div class="d-flex align-items-center gap-3">


                        <div class="user-avatar">

                            <i class="bi bi-person-fill fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <div class="fw-bold text-dark">

                                <?= $nomeUsuario ?>

                            </div>

                            <small class="text-muted">

                                Usuário autenticado

                            </small>

                        </div>


                    </div>


                </div>


            <?php else: ?>


                <!-- VISITANTE -->

                <div class="user-profile-card p-3 mb-4">


                    <div class="d-flex align-items-center gap-3">


                        <div class="user-avatar">

                            <i class="bi bi-person fs-4"></i>

                        </div>



                        <div>

                            <div class="fw-bold text-dark">

                                Bem-vindo ao portal!

                            </div>

                            <small class="text-muted">

                                Acesse sua conta para continuar.

                            </small>

                        </div>


                    </div>


                </div>


            <?php endif; ?>




            <!--  NAVEGAÇÃO PÚBLICA -->

            <div class="menu-section-title mb-2 px-3">

                NAVEGAÇÃO

            </div>



            <nav aria-label="Navegação mobile">


                <ul class="nav flex-column gap-1">


                    <!-- INÍCIO -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="/">

                            <i class="bi bi-house-door"></i>

                            Início

                        </a>

                    </li>



                    <!-- NOTÍCIAS -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="/painel/noticia_evento_aviso">

                            <i class="bi bi-newspaper"></i>

                            Notícias

                        </a>

                    </li>



                    <!-- EVENTOS -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="#">

                            <i class="bi bi-calendar-event"></i>

                            Eventos

                        </a>

                    </li>



                    <!-- CONTATO -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="#">

                            <i class="bi bi-envelope"></i>

                            Contato

                        </a>

                    </li>


                </ul>


            </nav>




            <!-- ÁREA DO USUÁRIO AUTENTICADO -->

            <?php if ($logado): ?>


                <hr class="my-4">


                <div class="menu-section-title mb-2 px-3">

                    MINHA CONTA

                </div>



                <ul class="nav flex-column gap-1">


                    <!-- MEU PERFIL -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="/perfil">

                            <i class="bi bi-person"></i>

                            Meu Perfil

                        </a>

                    </li>


                    <?php if(isset($_SESSION['id_tipo_adm'])): ?>
                    <!-- NOTÍCIAS, EVENTOS E AVISOS -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="/painel/noticia_evento_aviso">

                            <i class="bi bi-megaphone"></i>

                            Notícias, Eventos e Avisos

                        </a>

                    </li>



                    <!-- ÁREA ADMINISTRATIVA -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="/painel/gerenciamento">

                            <i class="bi bi-gear"></i>

                            Área Administrativa

                        </a>

                    </li>
                    <?php endif; ?>


                </ul>


            <?php endif; ?>




            <!-- ========================================
                 RODAPÉ DO MENU
            ======================================== -->

            <div class="mt-auto pt-4">


                <hr>



                <?php if ($logado): ?>


                    <!-- BOTÃO SAIR -->

                    <a
                        href="/logout"
                        class="btn btn-outline-danger w-100 fw-semibold d-flex justify-content-center align-items-center gap-2">

                        <i class="bi bi-box-arrow-right"></i>

                        Sair da conta

                    </a>


                <?php else: ?>


                    <!-- BOTÃO ENTRAR -->

                    <a
                        href="/login"
                        class="btn btn-primary w-100 fw-semibold d-flex justify-content-center align-items-center gap-2">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Entrar

                    </a>


                <?php endif; ?>



                <div class="text-center text-muted small mt-4">

                    Portal CETIDAM

                    <br>

                    &copy; 2026

                </div>


            </div>


        </div>


    </div>




    <!-- ========================================
         CONTEÚDO PRINCIPAL
    ======================================== -->

    <?= $content ?>




    <!-- ========================================
         FOOTER
    ======================================== -->

    <footer class="bg-dark text-white text-center py-1 mt-auto">


        <div class="container small opacity-75">


            <p class="mb-1">

                Colégio Estadual de Tempo Integral Doutor Aristides Maltez -
                &copy; 2026. Todos os direitos reservados.

            </p>



            <p class="mb-1">

                Rua Nova do Areal, s/n, Centro, Jaguaripe - BA.

            </p>


        </div>


    </footer>




    <!-- ========================================
         BOOTSTRAP JAVASCRIPT
    ======================================== -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




    <!-- ========================================
         MOSTRAR / OCULTAR SENHA
    ======================================== -->

    <script>
        const togglePassword = document.querySelector('#togglePassword');

        const password = document.querySelector('#password');

        const eyeIcon = document.querySelector('#eyeIcon');


        if (togglePassword && password && eyeIcon) {


            togglePassword.addEventListener('click', function() {


                const type = password.getAttribute('type') === 'password' ?
                    'text' :
                    'password';


                password.setAttribute('type', type);


                eyeIcon.classList.toggle('bi-eye-fill');

                eyeIcon.classList.toggle('bi-eye-slash-fill');


            });


        }
    </script>


</body>

</html>