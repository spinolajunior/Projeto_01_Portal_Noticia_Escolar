<main class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            <div class="mb-4">
                <h1 class="h3 mb-1">Cadastrar tipo de administrador</h1>
                <p class="text-body-secondary mb-0">Defina cargo e nível de acesso.</p>
            </div>
            <form class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <div class="col-12"><label for="cargo" class="form-label">Cargo *</label><input id="cargo" name="cargo" class="form-control" maxlength="100" required></div>
                        <div class="col-12"><label for="nivel_acesso" class="form-label">Nível de acesso *</label><input id="nivel_acesso" name="nivel_acesso" class="form-control" type="number" min="0" step="1" required>
                            <div class="form-text">Use o padrão de níveis definido pelo sistema.</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 p-md-4 pt-0 d-grid d-sm-flex justify-content-sm-end gap-2"><button type="reset" class="btn btn-light">Limpar</button><button type="submit" class="btn btn-primary">Cadastrar tipo</button></div>
            </form>
        </div>
    </div>
</main>