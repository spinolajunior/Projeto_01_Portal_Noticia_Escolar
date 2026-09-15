<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <img src="<?= htmlspecialchars($foto ?? 'view/img/user.png') ?>"
                            alt="Foto do aluno"
                            class="rounded-circle shadow-sm mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">

                        <h2 class="h4 fw-bold mb-1">
                            Editar perfil
                        </h2>

                        <p class="text-muted mb-0">
                            <i class="bi bi-mortarboard-fill me-1"></i>
                            Aluno
                        </p>
                    </div>

                    <form action="/usuario/editar" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="foto" class="form-label fw-semibold">
                                Foto de perfil
                            </label>
                            <input type="file"
                                class="form-control"
                                id="foto"
                                name="foto"
                                accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label fw-semibold">
                                Nome completo
                            </label>
                            <input type="text"
                                class="form-control"
                                id="nome"
                                name="nome"
                                value="<?= htmlspecialchars($nome ?? '') ?>"
                                required>
                        </div>

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="matricula" class="form-label fw-semibold">
                                    Matrícula
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="matricula"
                                    value="<?= htmlspecialchars($matricula ?? '') ?>"
                                    disabled>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="data_nascimento" class="form-label fw-semibold">
                                    Data de nascimento
                                </label>
                                <input type="date"
                                    class="form-control"
                                    id="data_nascimento"
                                    name="data_nascimento"
                                    value="<?= htmlspecialchars($data_nascimento ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12">
                                <label for="serie" class="form-label fw-semibold">
                                    Série
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="serie"
                                    name="serie"
                                    value="<?= htmlspecialchars($serie ?? '') ?>"
                                    required>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h3 class="h5 fw-bold mb-3">
                            <i class="bi bi-key me-2 text-primary"></i>
                            Credenciais
                        </h3>

                        <div class="mb-3">
                            <label for="usuario" class="form-label fw-semibold">
                                Usuário
                            </label>
                            <input type="text"
                                class="form-control"
                                id="usuario"
                                value="<?= htmlspecialchars($usuario ?? '') ?>"
                                disabled>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label fw-semibold">
                                Nova senha
                            </label>
                            <input type="password"
                                class="form-control"
                                id="senha"
                                name="senha"
                                placeholder="Digite apenas se quiser alterar">
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="/usuario/perfil" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                Salvar alterações
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</main>
```