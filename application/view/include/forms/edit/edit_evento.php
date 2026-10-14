<?php
$evento = $model['evento'];
$dataEvento = substr((string)($evento->data_evento ?? ''), 0, 10);
$esc = fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<main class="container py-4 py-lg-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-3 p-md-4">
                    <h1 class="h4 mb-1 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Evento</h1>
                    <p class="mb-0 small text-white-50">Atualize as informações do evento selecionado.</p>
                </div>
                <div class="card-body p-3 p-md-4 p-lg-5">
                    <form id="formEditarEvento" method="POST" action="/atualizar/evento" novalidate>
                        <input type="hidden" name="id" value="<?= (int)$evento->id ?>">
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">Título do Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" minlength="5" maxlength="50" value="<?= $esc($evento->titulo ?? '') ?>" placeholder="Digite o título do evento" aria-describedby="erroTitulo contadorTitulo" required>
                            <div class="invalid-feedback" id="erroTitulo"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Entre 5 e 50 caracteres.</small>
                                <small class="text-muted" id="contadorTitulo">0/50</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="data_evento" class="form-label fw-semibold">Data do Evento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="data_evento" name="data_evento" value="<?= $esc($dataEvento) ?>" data-original="<?= $esc($dataEvento) ?>" aria-describedby="erroData descricaoData" required>
                            <div class="invalid-feedback" id="erroData"></div>
                            <div class="form-text" id="descricaoData">Informe uma data válida. Para reagendar, selecione a data atual ou uma futura.</div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="/painel/evento" class="btn btn-outline-secondary px-4"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
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
        const form = document.getElementById('formEditarEvento');
        const titulo = document.getElementById('titulo');
        const dataEvento = document.getElementById('data_evento');
        const erroTitulo = document.getElementById('erroTitulo');
        const erroData = document.getElementById('erroData');
        const contador = document.getElementById('contadorTitulo');
        const btnSalvar = document.getElementById('btnSalvar');
        const dataOriginal = dataEvento.dataset.original;
        let enviando = false;

        function hoje() {
            const data = new Date();
            const ano = data.getFullYear();
            const mes = String(data.getMonth() + 1).padStart(2, '0');
            const dia = String(data.getDate()).padStart(2, '0');
            return `${ano}-${mes}-${dia}`;
        }

        function atualizarEstado(campo, valido, mensagem, mostrar) {
            campo.classList.toggle('is-valid', mostrar && valido);
            campo.classList.toggle('is-invalid', mostrar && !valido);

            if (mostrar) campo.setAttribute('aria-invalid', String(!valido));
            else campo.removeAttribute('aria-invalid');

            const erro = campo === titulo ? erroTitulo : erroData;
            erro.textContent = mensagem;

            return valido;
        }

        function validarTitulo(mostrar = true) {
            const valor = titulo.value.trim();
            let mensagem = '';

            if (!valor) {
                mensagem = 'O título do evento é obrigatório.';
            } else if (valor.length < 5) {
                mensagem = 'O título precisa ter pelo menos 5 caracteres.';
            } else if (valor.length > 50) {
                mensagem = 'O título pode ter no máximo 50 caracteres.';
            }

            contador.textContent = `${valor.length}/50`;
            return atualizarEstado(titulo, mensagem === '', mensagem, mostrar);
        }

        function validarData(mostrar = true) {
            const valor = dataEvento.value;
            let mensagem = '';

            if (!valor) {
                mensagem = 'A data do evento é obrigatória.';
            } else if (!/^\d{4}-\d{2}-\d{2}$/.test(valor)) {
                mensagem = 'Informe uma data válida.';
            } else {
                const [ano, mes, dia] = valor.split('-').map(Number);
                const data = new Date(0);
                data.setFullYear(ano, mes - 1, dia);

                if (
                    data.getFullYear() !== ano ||
                    data.getMonth() !== mes - 1 ||
                    data.getDate() !== dia
                ) {
                    mensagem = 'A data informada não existe.';
                } else if (valor !== dataOriginal && valor < hoje()) {
                    mensagem = 'A nova data não pode ser anterior à data atual.';
                }
            }

            return atualizarEstado(dataEvento, mensagem === '', mensagem, mostrar);
        }

        titulo.addEventListener('input', () => validarTitulo());

        titulo.addEventListener('blur', () => {
            titulo.value = titulo.value.trim();
            validarTitulo();
        });

        dataEvento.addEventListener('input', () => validarData());
        dataEvento.addEventListener('change', () => validarData());
        dataEvento.addEventListener('blur', () => validarData());

        form.addEventListener('submit', event => {
            if (enviando) {
                event.preventDefault();
                return;
            }

            titulo.value = titulo.value.trim();

            const tituloValido = validarTitulo();
            const dataValida = validarData();

            if (!tituloValido || !dataValida) {
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
        validarData(false);
    });
</script>