<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <img src="<?= $foto ?? 'view/img/user.png' ?>"
                             alt="Foto do administrador"
                             class="rounded-circle shadow-sm mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">

                        <h2 class="h4 fw-bold mb-1">
                            <?= htmlspecialchars($nome ?? 'Nome do administrador') ?>
                        </h2>

                        <p class="text-muted mb-0">
                            <i class="bi bi-shield-check me-1"></i>
                            Administrador
                        </p>
                    </div>

                    <hr>

                    <h3 class="h5 fw-bold mb-3">
                        <i class="bi bi-person-vcard me-2 text-primary"></i>
                        Dados pessoais
                    </h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Nome completo</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($nome ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Matrícula</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($matricula ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">CPF</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($cpf ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <h3 class="h5 fw-bold mb-3 mt-4">
                        <i class="bi bi-shield-lock me-2 text-primary"></i>
                        Informações administrativas
                    </h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Cargo</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($cargo ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Nível de acesso</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($nivel_acesso ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <h3 class="h5 fw-bold mb-3 mt-4">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                        Contato
                    </h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">E-mail</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($email ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Telefone</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($tel ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <h3 class="h5 fw-bold mb-3 mt-4">
                        <i class="bi bi-geo-alt me-2 text-primary"></i>
                        Endereço
                    </h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Cidade</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($cidade ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">CEP</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($cep ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Bairro</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($bairro ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Rua</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($rua ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Complemento</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($complemento ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="/perfil/administrador/update" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar perfil
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>