<?php
$aviso = $model['aviso'];
?>
<main class="container py-4 py-lg-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-3 p-md-4">
                    <h1 class="h4 mb-1 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Aviso</h1>
                    <p class="mb-0 small text-white-50">Atualize as informações do aviso selecionado.</p>
                </div>
                <div class="card-body p-3 p-md-4 p-lg-5">
                    <form id="formEditarAviso" method="POST" action="/atualizar/aviso" novalidate>
                        <input type="hidden" name="id" value="<?= (int)$aviso->id ?>">
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">Título do Aviso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" minlength="5" maxlength="50" value="<?= htmlspecialchars($aviso->titulo ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" placeholder="Digite o título do aviso" aria-describedby="erroTitulo contadorTitulo" required>
                            <div class="invalid-feedback" id="erroTitulo"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Entre 5 e 50 caracteres.</small>
                                <small class="text-muted" id="contadorTitulo">0/50</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="ativo" class="form-label fw-semibold">Situação <span class="text-danger">*</span></label>
                            <select class="form-select" id="ativo" name="ativo" aria-describedby="erroAtivo" required>
                                <option value="1" <?= (int)$aviso->ativo === 1 ? 'selected' : '' ?>>Ativo</option>
                                <option value="0" <?= (int)$aviso->ativo === 0 ? 'selected' : '' ?>>Inativo</option>
                            </select>
                            <div class="invalid-feedback" id="erroAtivo"></div>
                            <div class="form-text">Avisos inativos não devem ser exibidos na área pública do portal.</div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="/painel/aviso" class="btn btn-outline-secondary px-4"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSalvar"><i class="bi bi-check-circle me-1"></i>Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formEditarAviso');
        const titulo = document.getElementById('titulo');
        const ativo = document.getElementById('ativo');
        const erroTitulo = document.getElementById('erroTitulo');
        const erroAtivo = document.getElementById('erroAtivo');
        const contador = document.getElementById('contadorTitulo');
        const btnSalvar = document.getElementById('btnSalvar');
        let enviando = false;

        function validarTitulo(mostrar = true) {
            const valor = titulo.value.trim();
            let mensagem = '';

            if (!valor) {
                mensagem = 'O título do aviso é obrigatório.';
            } else if (valor.length < 5) {
                mensagem = 'O título precisa ter pelo menos 5 caracteres.';
            } else if (valor.length > 50) {
                mensagem = 'O título pode ter no máximo 50 caracteres.';
            }

            const valido = mensagem === '';
            titulo.classList.toggle('is-valid', mostrar && valido);
            titulo.classList.toggle('is-invalid', mostrar && !valido);

            if (mostrar) titulo.setAttribute('aria-invalid', String(!valido));
            else titulo.removeAttribute('aria-invalid');

            erroTitulo.textContent = mensagem;
            contador.textContent = `${valor.length}/50`;

            return valido;
        }

        function validarAtivo(mostrar = true) {
            const valido = ['0', '1'].includes(ativo.value);

            ativo.classList.toggle('is-valid', mostrar && valido);
            ativo.classList.toggle('is-invalid', mostrar && !valido);

            if (mostrar) ativo.setAttribute('aria-invalid', String(!valido));
            else ativo.removeAttribute('aria-invalid');

            erroAtivo.textContent = valido ? '' : 'Selecione uma situação válida.';

            return valido;
        }

        titulo.addEventListener('input', () => validarTitulo());

        titulo.addEventListener('blur', () => {
            titulo.value = titulo.value.trim();
            validarTitulo();
        });

        ativo.addEventListener('change', () => validarAtivo());

        form.addEventListener('submit', event => {
            if (enviando) {
                event.preventDefault();
                return;
            }

            titulo.value = titulo.value.trim();

            const tituloValido = validarTitulo();
            const ativoValido = validarAtivo();

            if (!tituloValido || !ativoValido) {
                event.preventDefault();

                const primeiroErro = form.querySelector('.is-invalid');

                if (primeiroErro) {
                    primeiroErro.focus();
                    primeiroErro.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                return;
            }

            enviando = true;
            btnSalvar.disabled = true;
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Salvando alterações...';
        });

        validarTitulo(false);
        validarAtivo(false);
    });
</script>