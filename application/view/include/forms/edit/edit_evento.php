<?php

$evento = $model['evento'];

$dataEvento = substr(
    (string) ($evento->data_evento ?? ''),
    0,
    10
);

?>

<main class="container py-4 py-lg-5 flex-grow-1">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-10 col-xl-9">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <!-- CABEÇALHO -->
                <div class="card-header bg-primary text-white p-3 p-md-4">

                    <h4 class="mb-1 fw-bold">

                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Evento

                    </h4>

                    <p class="mb-0 small text-white-50">
                        Atualize as informações do evento selecionado.
                    </p>

                </div>

                <!-- FORMULÁRIO -->
                <div class="card-body p-3 p-md-4 p-lg-5">

                    <form method="POST" action="/atualizar/evento">

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= (int) $evento->id ?>">

                        <!-- TÍTULO -->
                        <div class="mb-3">

                            <label for="titulo" class="form-label fw-semibold">
                                Título do Evento
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="titulo"
                                name="titulo"
                                maxlength="50"
                                value="<?= htmlspecialchars($evento->titulo, ENT_QUOTES, 'UTF-8') ?>"
                                required>

                        </div>

                        <!-- DATA -->
                        <div class="mb-4">

                            <label for="data_evento" class="form-label fw-semibold">
                                Data do Evento
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="data_evento"
                                name="data_evento"
                                value="<?= htmlspecialchars($dataEvento, ENT_QUOTES, 'UTF-8') ?>"
                                required>

                        </div>

                        <hr class="my-4">

                        <!-- BOTÕES -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">

                            <a
                                href="/painel/evento"
                                class="btn btn-outline-secondary px-4">

                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4 fw-semibold">

                                <i class="bi bi-check-circle me-1"></i>
                                Salvar Alterações

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>