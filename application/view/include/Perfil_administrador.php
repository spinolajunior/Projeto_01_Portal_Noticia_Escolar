<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <img src="<?= $foto ?? 'view/img/user.png' ?>"
                                alt="Foto do administrador"
                                class="rounded-circle img-fluid shadow-sm"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>

                        <h2 class="h4 fw-bold mb-1"><?= $nome ?? 'Nome do administrador' ?></h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-shield-check me-1"></i>
                            Administrador
                        </p>
                    </div>

                    <hr>

                    <h3 class="h5 fw-bold mb-3">
                        <i class="bi bi-person-vcard me-2 text-primary"></i>
                        Dados do administrador
                    </h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Nome completo</small>
                                <span class="fw-semibold"><?= $nome ?? 'Não informado' ?></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">E-mail</small>
                                <span class="fw-semibold"><?= $email ?? 'Não informado' ?></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Telefone</small>
                                <span class="fw-semibold"><?= $telefone ?? 'Não informado' ?></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Usuário</small>
                                <span class="fw-semibold"><?= $usuario ?? 'Não informado' ?></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block">Tipo de usuário</small>
                                <span class="fw-semibold">
                                    <i class="bi bi-shield-check me-1 text-primary"></i>
                                    Administrador
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="editar_perfil.php" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>
                            Editar perfil
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>