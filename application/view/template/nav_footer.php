<?php
$secao = (object)$_SESSION;
$logado = \controller\LoginController::logado();

$esc = static fn($valor): string => htmlspecialchars(
    (string)($valor ?? ''),
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);

$nomeUsuario = trim((string)($_SESSION['nome'] ?? 'Minha conta'));
if ($nomeUsuario === '') $nomeUsuario = 'Minha conta';

$nivelAcesso = (int)($_SESSION['acesso'] ?? $_SESSION['nivel'] ?? 0);
$podeGerenciar = $logado && $nivelAcesso >= 1;

$fotoPadrao = '/view/img/upload/perfil_usuario/user_def.jpg';
$fotoUsuario = $fotoPadrao;
$fotoBanco = trim((string)($secao->foto ?? ''));
$nomeArquivo = basename(str_replace('\\', '/', $fotoBanco));

if ($logado && $nomeArquivo !== '' && !in_array($nomeArquivo, ['.', '..'], true)) {
    $diretorioFotos = '/view/img/upload/perfil_usuario/';
    $raizes = array_unique(array_filter([
        $_SERVER['DOCUMENT_ROOT'] ?? '',
        defined('BASE_DIR') ? BASE_DIR : ''
    ]));

    foreach ($raizes as $raiz) {
        $caminhoFisico = rtrim($raiz, '/\\') . $diretorioFotos . $nomeArquivo;

        if (is_file($caminhoFisico) && is_readable($caminhoFisico)) {
            $fotoUsuario = $diretorioFotos . rawurlencode($nomeArquivo);
            break;
        }
    }
}

$flashTipo = $flash['tipo'] ?? 'warning';

if (!in_array($flashTipo, ['success', 'warning', 'danger', 'info', 'primary', 'secondary'], true)) {
    $flashTipo = 'warning';
}

$caminhoAtual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esc($titulo ?? 'Portal CETIDAM') ?></title>

    <link rel="icon" href="/view/img/logo_estado_da_bahia.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        .portal-header {
            background: linear-gradient(105deg, #0b5ed7, #0d6efd 65%, #1264e5);
            box-shadow: 0 3px 14px rgba(15, 45, 95, .12);
        }

        .portal-navbar {
            padding: 11px 0;
        }

        .portal-nav-container {
            width: calc(100% - clamp(64px, 12vw, 220px));
            max-width: 1440px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            align-items: center;
            gap: 20px;
        }

        .portal-brand {
            display: flex;
            align-items: center;
            justify-self: start;
            gap: 12px;
            min-width: 0;
            margin: 0;
            text-decoration: none;
        }

        .portal-logo {
            width: auto;
            height: 47px;
            max-width: 54px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .portal-brand-info {
            min-width: 0;
        }

        .portal-title {
            font-size: clamp(.72rem, .96vw, .95rem);
            font-weight: 750;
            line-height: 1.25;
            color: #fff;
            margin: 0;
            letter-spacing: -.15px;
            overflow-wrap: anywhere;
        }

        .portal-subtitle {
            display: block;
            margin-top: 3px;
            font-size: .69rem;
            line-height: 1.2;
            letter-spacing: .2px;
            color: rgba(255, 255, 255, .78);
        }

        .portal-desktop-links {
            justify-self: center;
        }

        .portal-menu-desktop {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .portal-menu-desktop .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 9px;
            font-size: .92rem;
            font-weight: 500;
            color: rgba(255, 255, 255, .88);
            white-space: nowrap;
            transition: background .2s, color .2s;
        }

        .portal-menu-desktop .nav-link i {
            font-size: 1.05rem;
        }

        .portal-menu-desktop .nav-link:hover,
        .portal-menu-desktop .nav-link:focus,
        .portal-menu-desktop .nav-link.ativo {
            background: rgba(255, 255, 255, .14);
            color: #fff;
        }

        .portal-menu-desktop .nav-link.ativo {
            font-weight: 600;
        }

        .portal-desktop-account {
            justify-self: end;
            min-width: 0;
        }

        .portal-user-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            max-width: 245px;
            padding: 5px 11px 5px 5px;
            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 30px;
            color: #fff;
            font-size: .88rem;
            font-weight: 600;
            transition: background .2s, border-color .2s;
        }

        .portal-user-button:hover,
        .portal-user-button:focus,
        .portal-user-button.show {
            background: rgba(255, 255, 255, .22);
            border-color: rgba(255, 255, 255, .38);
            color: #fff;
        }

        .portal-user-button::after {
            margin-left: 2px;
            opacity: .85;
        }

        .portal-user-avatar {
            width: 37px;
            height: 37px;
            min-width: 37px;
            border-radius: 50%;
            object-fit: cover;
            background: #e2e8f0;
            border: 2px solid rgba(255, 255, 255, .9);
        }

        .portal-user-texto {
            display: block;
            max-width: 145px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .portal-login-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 19px;
            border-radius: 9px;
            background: #fff;
            color: #0d6efd;
            border: 1px solid rgba(255, 255, 255, .8);
            font-size: .88rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .portal-login-button:hover {
            background: #edf4ff;
            color: #0a58ca;
        }

        .portal-dropdown {
            min-width: 245px;
            padding: 7px;
            border-radius: 12px;
            border: 1px solid #e5eaf0;
            box-shadow: 0 8px 25px rgba(15, 45, 95, .12);
        }

        .portal-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 7px;
            font-size: .88rem;
            white-space: normal;
        }

        .portal-dropdown .dropdown-item i {
            width: 19px;
            flex-shrink: 0;
        }

        .portal-dropdown .dropdown-item:hover {
            background: #f0f6ff;
        }

        .portal-dropdown .dropdown-item.text-danger:hover {
            background: #fff1f2;
        }

        .portal-mobile-toggle {
            width: 43px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: auto;
            padding: 0;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 9px;
            background: rgba(255, 255, 255, .1);
            flex-shrink: 0;
        }

        .portal-mobile-toggle:hover,
        .portal-mobile-toggle:focus {
            background: rgba(255, 255, 255, .2);
            color: #fff;
            box-shadow: none;
        }

        .portal-offcanvas {
            --bs-offcanvas-width: min(88vw, 350px);
        }

        .portal-offcanvas .offcanvas-header {
            background: linear-gradient(110deg, #0b5ed7, #0d6efd);
        }

        .menu-section-title {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #6c757d;
        }

        .portal-offcanvas .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border-radius: 9px;
            font-size: .92rem;
            font-weight: 500;
            color: #495057;
            transition: background .2s, color .2s;
        }

        .portal-offcanvas .nav-link:hover,
        .portal-offcanvas .nav-link:focus,
        .portal-offcanvas .nav-link.ativo {
            background: #e9f2ff;
            color: #0d6efd;
        }

        .portal-offcanvas .nav-link.ativo {
            font-weight: 700;
        }

        .portal-offcanvas .nav-link i {
            width: 23px;
            flex-shrink: 0;
            font-size: 1.05rem;
        }

        .user-profile-card {
            background: #f1f6ff;
            border: 1px solid #dce9ff;
            border-radius: 12px;
        }

        .user-avatar-mobile {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 50%;
            object-fit: cover;
            background: #e2e8f0;
            border: 2px solid #fff;
        }

        .user-avatar-visitante {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d6efd;
            color: #fff;
        }

        .portal-mobile-nome {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .flash-message {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        @media(max-width:1199.98px) {
            .portal-nav-container {
                width: calc(100% - 48px);
                max-width: 100%;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .portal-brand {
                flex: 1;
            }
        }

        @media(max-width:575.98px) {
            .portal-navbar {
                padding: 9px 0;
            }

            .portal-nav-container {
                width: calc(100% - 30px);
                gap: 8px;
            }

            .portal-brand {
                gap: 8px;
            }

            .portal-logo {
                height: 39px;
                max-width: 43px;
            }

            .portal-title {
                font-size: .72rem;
            }

            .portal-subtitle {
                font-size: .58rem;
                margin-top: 2px;
            }

            .portal-mobile-toggle {
                width: 39px;
                height: 38px;
            }

            .flash-alert {
                font-size: .9rem;
                padding-top: .875rem;
                padding-bottom: .875rem;
            }
        }

        @media(max-width:370px) {
            .portal-title {
                font-size: .65rem;
            }

            .portal-subtitle {
                font-size: .53rem;
            }
        }
    </style>
</head>

<body class="bg-body-secondary d-flex flex-column min-vh-100">

    <header class="portal-header">
        <nav class="navbar navbar-dark portal-navbar">
            <div class="portal-nav-container">

                <a href="/" class="portal-brand">
                    <img
                        src="/view/img/logo_estado_da_bahia.png"
                        alt="Logo do Colégio Estadual Doutor Aristides Maltez"
                        class="portal-logo">

                    <div class="portal-brand-info">
                        <h1 class="portal-title text-uppercase">
                            Colégio Estadual Doutor Aristides Maltez
                        </h1>

                        <small class="portal-subtitle text-uppercase">
                            Ministério da Educação
                        </small>
                    </div>
                </a>

                <div class="portal-desktop-links d-none d-xl-flex">
                    <ul class="navbar-nav portal-menu-desktop flex-row">

                        <li class="nav-item">
                            <a
                                href="/"
                                class="nav-link <?= $caminhoAtual === '/' ? 'ativo' : '' ?>"
                                <?= $caminhoAtual === '/' ? 'aria-current="page"' : '' ?>>
                                <i class="bi bi-house-door"></i>
                                Início
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                href="/cetidam"
                                class="nav-link <?= $caminhoAtual === '/cetidam' ? 'ativo' : '' ?>"
                                <?= $caminhoAtual === '/cetidam' ? 'aria-current="page"' : '' ?>>
                                <i class="bi bi-mortarboard-fill"></i>
                                CETIDAM
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                href="/contato"
                                class="nav-link <?= $caminhoAtual === '/contato' ? 'ativo' : '' ?>"
                                <?= $caminhoAtual === '/contato' ? 'aria-current="page"' : '' ?>>
                                <i class="bi bi-envelope"></i>
                                Contato
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="portal-desktop-account d-none d-xl-flex align-items-center">
                    <?php if ($logado): ?>

                        <div class="dropdown">
                            <button
                                class="btn portal-user-button dropdown-toggle"
                                type="button"
                                id="dropdownUserDesktop"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <img
                                    src="<?= $esc($fotoUsuario) ?>"
                                    alt="Foto de <?= $esc($nomeUsuario) ?>"
                                    class="portal-user-avatar"
                                    onerror="this.onerror=null;this.src='/view/img/upload/perfil_usuario/user_def.jpg';">

                                <span class="portal-user-texto">
                                    <?= $esc($nomeUsuario) ?>
                                </span>
                            </button>

                            <ul
                                class="dropdown-menu dropdown-menu-end portal-dropdown mt-2"
                                aria-labelledby="dropdownUserDesktop">

                                <li>
                                    <a class="dropdown-item" href="/perfil">
                                        <i class="bi bi-person text-primary"></i>
                                        Meu Perfil
                                    </a>
                                </li>

                                <?php if ($podeGerenciar): ?>

                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="/painel/noticia_evento_aviso">
                                            <i class="bi bi-megaphone text-primary"></i>
                                            Notícias, Eventos e Avisos
                                        </a>
                                    </li>

                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="/painel/gerenciamento">
                                            <i class="bi bi-gear text-primary"></i>
                                            Área Administrativa
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                <?php endif; ?>

                                <li>
                                    <a
                                        class="dropdown-item text-danger fw-semibold"
                                        href="/logout">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </div>

                    <?php else: ?>

                        <a href="/login" class="portal-login-button">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Entrar
                        </a>

                    <?php endif; ?>
                </div>

                <button
                    class="navbar-toggler portal-mobile-toggle d-xl-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#menuMobile"
                    aria-controls="menuMobile"
                    aria-expanded="false"
                    aria-label="Abrir menu">

                    <i class="bi bi-list fs-4"></i>
                </button>

            </div>
        </nav>
    </header>

    <div
        class="offcanvas offcanvas-end portal-offcanvas d-xl-none"
        tabindex="-1"
        id="menuMobile"
        aria-labelledby="menuMobileLabel">

        <div class="offcanvas-header text-white px-4 py-3">
            <div>
                <h5 class="offcanvas-title fw-bold" id="menuMobileLabel">
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

        <div class="offcanvas-body d-flex flex-column p-3">

            <?php if ($logado): ?>

                <div class="user-profile-card p-3 mb-4">
                    <div class="d-flex align-items-center gap-3">

                        <img
                            src="<?= $esc($fotoUsuario) ?>"
                            alt="Foto de perfil de <?= $esc($nomeUsuario) ?>"
                            class="user-avatar-mobile"
                            onerror="this.onerror=null;this.src='/view/img/upload/perfil_usuario/user_def.jpg';">

                        <div class="flex-grow-1 portal-mobile-nome">
                            <div class="fw-bold text-dark">
                                <?= $esc($nomeUsuario) ?>
                            </div>

                            <small class="text-muted">
                                Usuário autenticado
                            </small>
                        </div>
                    </div>
                </div>

            <?php else: ?>

                <div class="user-profile-card p-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar-mobile user-avatar-visitante">
                            <i class="bi bi-person fs-4"></i>
                        </div>

                        <div class="flex-grow-1">
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

            <div class="menu-section-title px-3 mb-2">
                NAVEGAÇÃO
            </div>

            <nav aria-label="Navegação mobile">
                <ul class="nav flex-column gap-1">

                    <li class="nav-item">
                        <a
                            class="nav-link <?= $caminhoAtual === '/' ? 'ativo' : '' ?>"
                            href="/"
                            <?= $caminhoAtual === '/' ? 'aria-current="page"' : '' ?>>

                            <i class="bi bi-house-door"></i>
                            Início
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= $caminhoAtual === '/cetidam' ? 'ativo' : '' ?>"
                            href="/cetidam"
                            <?= $caminhoAtual === '/cetidam' ? 'aria-current="page"' : '' ?>>

                            <i class="bi bi-mortarboard-fill"></i>
                            CETIDAM
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= $caminhoAtual === '/contato' ? 'ativo' : '' ?>"
                            href="/contato"
                            <?= $caminhoAtual === '/contato' ? 'aria-current="page"' : '' ?>>

                            <i class="bi bi-envelope"></i>
                            Contato
                        </a>
                    </li>

                </ul>
            </nav>

            <?php if ($logado): ?>

                <hr class="my-4">

                <div class="menu-section-title mb-2 px-3">
                    MINHA CONTA
                </div>

                <ul class="nav flex-column gap-1">

                    <li class="nav-item">
                        <a class="nav-link" href="/perfil">
                            <i class="bi bi-person"></i>
                            Meu Perfil
                        </a>
                    </li>

                    <?php if ($podeGerenciar): ?>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/painel/noticia_evento_aviso">

                                <i class="bi bi-megaphone"></i>
                                Notícias, Eventos e Avisos
                            </a>
                        </li>

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

            <div class="mt-auto pt-4">
                <hr>

                <?php if ($logado): ?>

                    <a
                        href="/logout"
                        class="btn btn-outline-danger w-100 fw-semibold d-flex justify-content-center align-items-center gap-2">

                        <i class="bi bi-box-arrow-right"></i>
                        Sair da conta
                    </a>

                <?php else: ?>

                    <a
                        href="/login"
                        class="btn btn-primary w-100 fw-semibold d-flex justify-content-center align-items-center gap-2">

                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar
                    </a>

                <?php endif; ?>

                <div class="text-center text-muted small mt-4">
                    Portal CETIDAM
                    <div>&copy; <?= date('Y') ?></div>
                </div>
            </div>

        </div>
    </div>

    <?php if (isset($flash) && is_array($flash)): ?>

        <div class="container mt-3" id="flashContainer">
            <div
                id="flashMsg"
                class="alert alert-<?= $esc($flashTipo) ?> alert-dismissible fade show shadow-sm d-flex align-items-start gap-2 flash-alert"
                role="alert">

                <?php if ($flashTipo === 'success'): ?>

                    <i
                        class="bi bi-check-circle-fill flex-shrink-0"
                        aria-hidden="true"></i>

                <?php elseif ($flashTipo === 'warning'): ?>

                    <i
                        class="bi bi-exclamation-triangle-fill flex-shrink-0"
                        aria-hidden="true"></i>

                <?php else: ?>

                    <i
                        class="bi bi-x-circle-fill flex-shrink-0"
                        aria-hidden="true"></i>

                <?php endif; ?>

                <span class="flex-grow-1 flash-message">
                    <?= $esc($flash['msg'] ?? '') ?>
                </span>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Fechar mensagem">
                </button>
            </div>
        </div>

    <?php endif; ?>

    <?= $content ?>

    <footer id="contato" class="bg-dark text-white text-center py-2 mt-auto">
        <div class="container small opacity-75">
            <p class="mb-1">
                Colégio Estadual de Tempo Integral Doutor Aristides Maltez -
                &copy; <?= date('Y') ?>. Todos os direitos reservados.
            </p>

            <p class="mb-0">
                <i class="bi bi-geo-alt me-1"></i>
                Rua Nova do Areal, s/n, Centro, Jaguaripe - BA.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flash = document.getElementById('flashMsg');

            if (flash) {
                flash.addEventListener('closed.bs.alert', () => {
                    document.getElementById('flashContainer')?.remove();
                });

                setTimeout(() => {
                    if (flash.isConnected) {
                        bootstrap.Alert.getOrCreateInstance(flash).close();
                    }
                }, 4000);
            }
        });
    </script>
</body>

</html>