<?php
$esc = static fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$nextUrl = $_GET['next_url'] ?? '';
$action = '/login';
if (is_string($nextUrl) && preg_match('/^\/(?!\/)[^\x00-\x20\x7F\\\\]*$/', $nextUrl)) {
    $action .= '?' . http_build_query(['next_url' => $nextUrl], '', '&', PHP_QUERY_RFC3986);
}
?>
<main class="container portal-login-main d-flex justify-content-center align-items-center py-4 py-md-5">
    <style>
        .portal-login-main {
            min-height: calc(100vh - 170px);
            min-height: calc(100svh - 170px)
        }

        .portal-login-card {
            max-width: 445px;
            border: 1px solid #e4e9f1 !important;
            border-top: 4px solid #0d6efd !important;
            border-radius: 13px;
            overflow: hidden;
            box-shadow: 0 5px 24px rgba(25, 43, 75, .07)
        }

        .portal-login-card .card-body {
            padding: 30px 34px
        }

        .portal-login-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf2ff;
            color: #0d6efd;
            border-radius: 12px;
            font-size: 1.45rem
        }

        .portal-login-title {
            font-size: 1.35rem;
            font-weight: 750;
            letter-spacing: -.4px;
            color: #1e293b
        }

        .portal-login-card .form-label {
            font-size: .88rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 7px
        }

        .portal-login-card .input-group {
            border-radius: 8px
        }

        .portal-login-card .input-group-text {
            background: #fff;
            color: #94a3b8;
            border-color: #dbe2ea;
            padding: 0 14px
        }

        .portal-login-card .form-control {
            min-height: 47px;
            font-size: .95rem;
            border-color: #dbe2ea;
            min-width: 0
        }

        .portal-login-card .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe
        }

        .portal-login-card .input-group:focus-within {
            box-shadow: 0 0 0 .18rem rgba(13, 110, 253, .1)
        }

        .portal-login-card .input-group:focus-within .input-group-text,
        .portal-login-card .input-group:focus-within .btn {
            border-color: #86b7fe
        }

        .portal-login-card .btn-visualizar {
            background: #fff;
            border-color: #dbe2ea;
            color: #64748b;
            min-width: 46px
        }

        .portal-login-card .btn-visualizar:hover {
            background: #f8fafc;
            color: #0d6efd
        }

        .portal-login-card .btn-entrar {
            min-height: 46px;
            border-radius: 8px;
            font-size: .95rem;
            transition: background .2s, transform .2s
        }

        .portal-login-card .btn-entrar:hover:not(:disabled) {
            transform: translateY(-1px)
        }

        .portal-login-card .form-check-input {
            cursor: pointer
        }

        .portal-login-card .form-check-label {
            cursor: pointer;
            color: #64748b;
            font-size: .87rem
        }

        .portal-login-card .login-footer {
            border-top: 1px solid #eef1f5;
            margin-top: 24px;
            padding-top: 18px;
            font-size: .82rem;
            color: #64748b;
            text-align: center
        }

        @media(max-width:576px) {
            .portal-login-main {
                min-height: calc(100svh - 150px);
                padding-left: 16px;
                padding-right: 16px
            }

            .portal-login-card .card-body {
                padding: 26px 22px
            }

            .portal-login-title {
                font-size: 1.2rem
            }

            .portal-login-icon {
                width: 44px;
                height: 44px;
                min-width: 44px
            }
        }
    </style>
    <div class="card portal-login-card w-100">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="portal-login-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <h1 class="portal-login-title mb-1">PORTAL CETIDAM</h1>
                    <div class="text-primary fw-semibold small">Acesso à sua conta</div>
                </div>
            </div>
            <p class="text-secondary small mb-4">Informe suas credenciais para acessar o portal escolar.</p>
            <form id="formLogin" action="<?= $esc($action) ?>" method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="usuario" name="usuario" placeholder="Seu nome de usuário" autocomplete="username" autocapitalize="none" spellcheck="false" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
                        <button type="button" class="btn btn-visualizar border-start-0" id="togglePassword" aria-label="Mostrar senha" aria-pressed="false">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div id="avisoCaps" class="small text-warning-emphasis mt-2 d-none" aria-live="polite">
                        <i class="bi bi-exclamation-triangle me-1"></i>Caps Lock está ativado.
                    </div>
                </div>
                <div class="form-check mt-3 mb-4">
                    <input class="form-check-input" type="checkbox" id="remember" name="checkLembrar" value="1">
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>
                <button type="submit" id="btnEntrar" class="btn btn-primary btn-entrar w-100 fw-semibold d-flex justify-content-center align-items-center gap-2">
                    <span id="textoEntrar">Entrar no portal</span>
                    <i class="bi bi-arrow-right" id="iconeEntrar"></i>
                    <span class="spinner-border spinner-border-sm d-none" id="spinnerLogin" aria-hidden="true"></span>
                </button>
                <div class="login-footer">
                    <div class="mb-2"><i class="bi bi-question-circle me-1"></i>Problemas para acessar sua conta?</div>
                    <span>Entre em contato com a secretaria da escola.</span>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formLogin');
        const senha = document.getElementById('senha');
        const avisoCaps = document.getElementById('avisoCaps');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const btnEntrar = document.getElementById('btnEntrar');
        const textoEntrar = document.getElementById('textoEntrar');
        const iconeEntrar = document.getElementById('iconeEntrar');
        const spinnerLogin = document.getElementById('spinnerLogin');
        let enviando = false;

        togglePassword.addEventListener('click', () => {
            const mostrar = senha.type === 'password';
            senha.type = mostrar ? 'text' : 'password';
            eyeIcon.className = mostrar ? 'bi bi-eye-slash' : 'bi bi-eye';
            togglePassword.setAttribute('aria-label', mostrar ? 'Ocultar senha' : 'Mostrar senha');
            togglePassword.setAttribute('aria-pressed', String(mostrar));
            senha.focus();
        });

        function verificarCaps(event) {
            if (typeof event.getModifierState === 'function') {
                avisoCaps.classList.toggle('d-none', !event.getModifierState('CapsLock'));
            }
        }

        senha.addEventListener('keydown', verificarCaps);
        senha.addEventListener('keyup', verificarCaps);
        senha.addEventListener('blur', () => avisoCaps.classList.add('d-none'));

        form.addEventListener('submit', event => {
            if (enviando) {
                event.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            enviando = true;
            btnEntrar.disabled = true;
            textoEntrar.textContent = 'Entrando...';
            iconeEntrar.classList.add('d-none');
            spinnerLogin.classList.remove('d-none');
        });

        window.addEventListener('pageshow', () => {
            enviando = false;
            btnEntrar.disabled = false;
            textoEntrar.textContent = 'Entrar no portal';
            iconeEntrar.classList.remove('d-none');
            spinnerLogin.classList.add('d-none');
        });
    });
</script>