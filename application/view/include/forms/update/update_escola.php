<main class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="mb-4">
                <h1 class="h3 mb-1">Editar escola</h1>
                <p class="text-body-secondary mb-0">Altere os dados necessários e salve.</p>
            </div>
            <form class="card shadow-sm border-0" enctype="multipart/form-data">
                <div class="card-body p-3 p-md-4"><input type="hidden" name="id">
                    <div class="row g-3">
                        <div class="col-12"><label for="nome" class="form-label">Nome da escola *</label><input id="nome" name="nome" class="form-control" maxlength="100" required></div>
                        <div class="col-12 col-md-6"><label for="cod_inep" class="form-label">Código INEP *</label><input id="cod_inep" name="cod_inep" class="form-control" maxlength="100" required></div>
                        <div class="col-12 col-md-6"><label for="ano_letivo" class="form-label">Ano letivo *</label><input id="ano_letivo" name="ano_letivo" class="form-control" type="number" min="1901" max="2155" required></div>
                        <div class="col-12 col-md-6"><label for="id_contato" class="form-label">Contato *</label><select id="id_contato" name="id_contato" class="form-select" required>
                                <option value="">Selecione o contato</option>
                            </select></div>
                        <div class="col-12 col-md-6"><label for="id_endereco" class="form-label">Endereço *</label><select id="id_endereco" name="id_endereco" class="form-select" required>
                                <option value="">Selecione o endereço</option>
                            </select></div>
                        <div class="col-12"><label for="logo_img" class="form-label">Logotipo</label><input id="logo_img" name="logo_img" class="form-control" type="file" accept="image/*"></div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 p-md-4 pt-0 d-grid d-sm-flex justify-content-sm-end gap-2"><button type="button" class="btn btn-light">Cancelar</button><button type="submit" class="btn btn-primary">Salvar alterações</button></div>
            </form>
        </div>
    </div>
</main>