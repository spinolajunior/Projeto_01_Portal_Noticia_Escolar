<main class="container my-5">

    <div class="row justify-content-center">

        <div class="col-12 col-md-10 col-lg-8">

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

                <!-- CABEÇALHO -->
                <div class="card-header bg-primary text-white p-3 p-md-4">

                    <h1 class="h4 card-title mb-1 fw-bold">

                        <i class="bi bi-megaphone me-2"></i>
                        Novo Aviso

                    </h1>

                    <p class="mb-0 small text-white-50">
                        Cadastre um novo aviso para o portal escolar.
                    </p>

                </div>

                <!-- FORMULÁRIO -->
                <div class="card-body p-3 p-md-4">

                    <form action="/cadastro/aviso" method="POST">

                        <!-- TÍTULO -->
                        <div class="mb-4">

                            <label for="titulo" class="form-label fw-semibold">
                                Título do Aviso
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="titulo"
                                name="titulo"
                                maxlength="50"
                                placeholder="Digite o aviso"
                                required>

                            <div class="form-text">
                                Informe o comunicado que será apresentado no portal.
                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- BOTÕES -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">

                            <a
                                href="/painel/aviso"
                                class="btn btn-outline-secondary px-4">

                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4 fw-semibold">

                                <i class="bi bi-check-circle me-1"></i>
                                Salvar Aviso

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>