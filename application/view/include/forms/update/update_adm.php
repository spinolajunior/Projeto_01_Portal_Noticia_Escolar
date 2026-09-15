<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <img src="<?= htmlspecialchars($foto ?? 'view/img/user.png') ?>"
                            alt="Foto do administrador"
                            class="rounded-circle shadow-sm mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">

                        <h2 class="h4 fw-bold mb-1">
                            Editar perfil
                        </h2>

                        <p class="text-muted mb-0">
                            <i class="bi bi-shield-check me-1"></i>
                            Administrador
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

                        <h3 class="h5 fw-bold mb-3">
                            <i class="bi bi-person-vcard me-2 text-primary"></i>
                            Dados pessoais
                        </h3>

                        <div class="row g-3">

                            <div class="col-12">
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
                                <label for="cpf" class="form-label fw-semibold">
                                    CPF
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="cpf"
                                    value="<?= htmlspecialchars($cpf ?? '') ?>"
                                    disabled>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h3 class="h5 fw-bold mb-3">
                            <i class="bi bi-shield-lock me-2 text-primary"></i>
                            Informações administrativas
                        </h3>

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="cargo" class="form-label fw-semibold">
                                    Cargo
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="cargo"
                                    value="<?= htmlspecialchars($cargo ?? '') ?>"
                                    disabled>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="nivel_acesso" class="form-label fw-semibold">
                                    Nível de acesso
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="nivel_acesso"
                                    value="<?= htmlspecialchars($nivel_acesso ?? '') ?>"
                                    disabled>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h3 class="h5 fw-bold mb-3">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                            Contato
                        </h3>

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    E-mail
                                </label>
                                <input type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="<?= htmlspecialchars($email ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="tel" class="form-label fw-semibold">
                                    Telefone
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="tel"
                                    name="tel"
                                    value="<?= htmlspecialchars($tel ?? '') ?>"
                                    required>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h3 class="h5 fw-bold mb-3">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            Endereço
                        </h3>

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="cidade" class="form-label fw-semibold">
                                    Cidade
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="cidade"
                                    name="cidade"
                                    value="<?= htmlspecialchars($cidade ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="cep" class="form-label fw-semibold">
                                    CEP
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="cep"
                                    name="cep"
                                    value="<?= htmlspecialchars($cep ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="bairro" class="form-label fw-semibold">
                                    Bairro
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="bairro"
                                    name="bairro"
                                    value="<?= htmlspecialchars($bairro ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="rua" class="form-label fw-semibold">
                                    Rua
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="rua"
                                    name="rua"
                                    value="<?= htmlspecialchars($rua ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12">
                                <label for="complemento" class="form-label fw-semibold">
                                    Complemento
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="complemento"
                                    name="complemento"
                                    value="<?= htmlspecialchars($complemento ?? '') ?>">
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