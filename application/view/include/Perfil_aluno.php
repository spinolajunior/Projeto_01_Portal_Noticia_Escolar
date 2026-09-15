<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <img src="<?= $foto ?? 'view/img/user.png' ?>"
                             alt="Foto do aluno"
                             class="rounded-circle shadow-sm mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">

                        <h2 class="h4 fw-bold mb-1">
                            <?= htmlspecialchars($nome ?? 'Nome do aluno') ?>
                        </h2>

                        <p class="text-muted mb-0">
                            <i class="bi bi-mortarboard-fill me-1"></i>
                            Aluno
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
                                <small class="text-muted d-block">Data de nascimento</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($data_nascimento ?? 'Não informado') ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Série</small>
                                <span class="fw-semibold">
                                    <?= htmlspecialchars($serie ?? 'Não informado') ?>
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

                    <div class="d-flex justify-content-end mt-4">
                        <a href="/aluno/update" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar perfil
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>