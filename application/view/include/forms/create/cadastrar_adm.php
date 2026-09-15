<main class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="mb-4">
                <h1 class="h3 mb-1">Cadastrar administrador</h1>
                <p class="text-body-secondary mb-0">Dados funcionais, acesso e vínculos do administrador.</p>
            </div>
            <form class="card shadow-sm border-0" enctype="multipart/form-data">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <div class="col-12"><label for="nome" class="form-label">Nome completo *</label><input id="nome" name="nome" class="form-control" maxlength="100" required></div>
                        <div class="col-12 col-md-6"><label for="matricula" class="form-label">Matrícula *</label><input id="matricula" name="matricula" class="form-control" maxlength="100" required></div>
                        <div class="col-12 col-md-6"><label for="cpf" class="form-label">CPF *</label><input id="cpf" name="cpf" class="form-control" maxlength="50" inputmode="numeric" required></div>
                        <div class="col-12 col-md-6"><label for="id_tipo_adm" class="form-label">Tipo de administrador</label><select id="id_tipo_adm" name="id_tipo_adm" class="form-select">
                                <option value="">Selecione o tipo</option>
                            </select></div>
                        <div class="col-12 col-md-6"><label for="id_credenciais" class="form-label">Credenciais de acesso</label><select id="id_credenciais" name="id_credenciais" class="form-select">
                                <option value="">Selecione as credenciais</option>
                            </select></div>
                        <div class="col-12 col-md-6"><label for="id_contato" class="form-label">Contato</label><select id="id_contato" name="id_contato" class="form-select">
                                <option value="">Selecione o contato</option>
                            </select></div>
                        <div class="col-12 col-md-6"><label for="id_endereco" class="form-label">Endereço</label><select id="id_endereco" name="id_endereco" class="form-select">
                                <option value="">Selecione o endereço</option>
                            </select></div>
                        <div class="col-12"><label for="foto" class="form-label">Foto</label><input id="foto" name="foto" class="form-control" type="file" accept="image/*"></div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 p-md-4 pt-0 d-grid d-sm-flex justify-content-sm-end gap-2"><button type="reset" class="btn btn-light">Limpar</button><button type="submit" class="btn btn-primary">Cadastrar administrador</button></div>
            </form>
        </div>
    </div>
</main>