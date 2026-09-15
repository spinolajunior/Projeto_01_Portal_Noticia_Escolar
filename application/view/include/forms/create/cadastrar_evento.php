<main class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="mb-4">
                <h1 class="h3 mb-1">Cadastrar evento</h1>
                <p class="text-body-secondary mb-0">Informe os dados do evento escolar.</p>
            </div>
            <form class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-7"><label for="titulo" class="form-label">Título *</label><input id="titulo" name="titulo" class="form-control" maxlength="30" required></div>
                        <div class="col-12 col-md-5"><label for="data_evento" class="form-label">Data e horário *</label><input id="data_evento" name="data_evento" class="form-control" type="datetime-local" required></div>
                        <div class="col-12"><label for="id_administrador" class="form-label">Administrador responsável</label><select id="id_administrador" name="id_administrador" class="form-select">
                                <option value="">Selecione um administrador</option>
                            </select></div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 p-md-4 pt-0 d-grid d-sm-flex justify-content-sm-end gap-2"><button type="reset" class="btn btn-light">Limpar</button><button type="submit" class="btn btn-primary">Cadastrar evento</button></div>
            </form>
        </div>
    </div>
</main>