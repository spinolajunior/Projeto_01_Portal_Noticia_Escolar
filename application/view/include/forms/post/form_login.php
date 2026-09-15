<main class="container my-auto py-5 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm border-0 border-top border-4 border-primary rounded-3 w-100" style="max-width: 420px;">
            <div class="card-body p-4 p-sm-5">

                <!-- Cabeçalho do Card -->
                <div class="text-center mb-4">
                    <h2 class="h4 fw-bold text-dark mb-1">Acesso ao Portal</h2>
                    <p class="text-secondary small">Insira suas credenciais para continuar</p>
                </div>

                <!-- Formulário -->
                <form action="/login" method="post">

                    <!-- Campo Usuário -->
                    <div class="mb-3">
                        <label for="usuario" class="form-label text-secondary fw-semibold small">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-secondary border-end-0">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
                        </div>
                    </div>

                    <!-- Campo Senha -->
                    <div class="mb-3">
                        <label for="senha" class="form-label text-secondary fw-semibold small">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-secondary border-end-0">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="senha" name="senha" placeholder="Digite sua senha" required>
                            <button class="btn btn-outline-secondary bg-white text-secondary border-start-0" type="button" id="togglePassword">
                                <i class="bi bi-eye-fill" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Lembrar-me / Esqueceu a Senha -->
                    <div class="d-flex justify-content-between align-items-center mb-4 small">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="checkLembrar">
                            <label class="form-check-label text-secondary" for="remember">Lembrar-me</label>
                        </div>
                        <a href="#" class="text-decoration-none fw-semibold link-primary">Esqueceu a senha?</a>
                    </div>

                    <!-- Botão de Login (btn-primary padrão) -->
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm d-flex justify-content-center align-items-center gap-2">
                        <span>Entrar</span>
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                    </button>
                </form>

            </div>
        </div>
    </main>