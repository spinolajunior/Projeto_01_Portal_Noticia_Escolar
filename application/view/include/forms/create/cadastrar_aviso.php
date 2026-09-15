<main class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="mb-4">
                <h1 class="h3 mb-1">Cadastrar aviso</h1>
                <p class="text-body-secondary mb-0">Campos com * são obrigatórios.</p>
            </div>
            <form class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <div class="col-12"><label for="titulo" class="form-label">Título *</label><input id="titulo" name="titulo" class="form-control" maxlength="50" required></div>
                        <div class="col-12 col-md-6"><label for="id_administrador" class="form-label">Administrador responsável</label><select id="id_administrador" name="id_administrador" class="form-select">
                                <option value="">Selecione um administrador</option>
                            </select></div>
                        <div class="col-12 col-md-6"><label class="form-label d-block">Status</label>
                            <div class="form-check form-switch pt-2"><input id="status" name="status" value="1" class="form-check-input" type="checkbox" checked><label for="status" class="form-check-label">Aviso ativo</label></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 p-md-4 pt-0 d-grid d-sm-flex justify-content-sm-end gap-2"><button type="reset" class="btn btn-light">Limpar</button><button type="submit" class="btn btn-primary">Cadastrar aviso</button></div>
            </form>
        </div>
    </div>
</main>