<main class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="mb-4">
                <h1 class="h3 mb-1">Cadastrar endereço</h1>
                <p class="text-body-secondary mb-0">Informe a localização completa.</p>
            </div>
            <form class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-4"><label for="cep" class="form-label">CEP *</label><input id="cep" name="cep" class="form-control" maxlength="50" required></div>
                        <div class="col-12 col-md-8"><label for="cidade" class="form-label">Cidade *</label><input id="cidade" name="cidade" class="form-control" maxlength="50" required></div>
                        <div class="col-12 col-md-6"><label for="bairro" class="form-label">Bairro *</label><input id="bairro" name="bairro" class="form-control" maxlength="50" required></div>
                        <div class="col-12 col-md-6"><label for="rua" class="form-label">Rua *</label><input id="rua" name="rua" class="form-control" maxlength="50" required></div>
                        <div class="col-12"><label for="complemento" class="form-label">Complemento</label><input id="complemento" name="complemento" class="form-control" maxlength="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 p-md-4 pt-0 d-grid d-sm-flex justify-content-sm-end gap-2"><button type="reset" class="btn btn-light">Limpar</button><button type="submit" class="btn btn-primary">Cadastrar endereço</button></div>
            </form>
        </div>
    </div>
</main>