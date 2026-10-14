<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-3 p-md-4">
                    <h1 class="h4 card-title mb-1 fw-bold"><i class="bi bi-calendar-event me-2"></i>Novo Evento</h1>
                    <p class="mb-0 small text-white-50">Cadastre um novo evento no portal escolar.</p>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form id="formEvento" action="/cadastro/evento" method="POST" novalidate>
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">Título do Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" minlength="5" maxlength="50" placeholder="Digite o título do evento" aria-describedby="erroTitulo contadorTitulo" required>
                            <div class="invalid-feedback" id="erroTitulo">Informe um título válido.</div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Entre 5 e 50 caracteres.</small>
                                <small class="text-muted" id="contadorTitulo">0/50</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="data_evento" class="form-label fw-semibold">Data do Evento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="data_evento" name="data_evento" aria-describedby="erroData" required>
                            <div class="invalid-feedback" id="erroData">Selecione uma data válida.</div>
                            <div class="form-text">Selecione a data em que o evento acontecerá. Não são permitidas datas anteriores à atual.</div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="/painel/evento" class="btn btn-outline-secondary px-4"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSalvar"><i class="bi bi-check-circle me-1"></i>Salvar Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formEvento');
        const titulo = document.getElementById('titulo');
        const dataEvento = document.getElementById('data_evento');
        const erroTitulo = document.getElementById('erroTitulo');
        const erroData = document.getElementById('erroData');
        const contador = document.getElementById('contadorTitulo');
        const btnSalvar = document.getElementById('btnSalvar');
        let enviando = false;

        function dataAtual() {
            const agora = new Date();
            const ano = agora.getFullYear();
            const mes = String(agora.getMonth() + 1).padStart(2, '0');
            const dia = String(agora.getDate()).padStart(2, '0');
            return `${ano}-${mes}-${dia}`;
        }

        dataEvento.min = dataAtual();

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

            const valido = mensagem === '';
            titulo.classList.toggle('is-valid', mostrar && valido);
            titulo.classList.toggle('is-invalid', mostrar && !valido);
            titulo.setAttribute('aria-invalid', String(mostrar && !valido));
            erroTitulo.textContent = mensagem;
            contador.textContent = `${valor.length}/50`;
            return valido;
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
                const data = new Date(ano, mes - 1, dia);

                if (
                    data.getFullYear() !== ano ||
                    data.getMonth() !== mes - 1 ||
                    data.getDate() !== dia
                ) {
                    mensagem = 'A data informada não existe.';
                } else if (valor < dataAtual()) {
                    mensagem = 'A data do evento não pode ser anterior à data atual.';
                }
            }

            const valido = mensagem === '';
            dataEvento.classList.toggle('is-valid', mostrar && valido);
            dataEvento.classList.toggle('is-invalid', mostrar && !valido);
            dataEvento.setAttribute('aria-invalid', String(mostrar && !valido));
            erroData.textContent = mensagem;
            return valido;
        }

        titulo.addEventListener('input', () => validarTitulo());

        titulo.addEventListener('blur', () => {
            titulo.value = titulo.value.trim();
            validarTitulo();
        });

        dataEvento.addEventListener('input', () => validarData());
        dataEvento.addEventListener('change', () => validarData());

        form.addEventListener('submit', (event) => {
            if (enviando) {
                event.preventDefault();
                return;
            }

            titulo.value = titulo.value.trim();
            dataEvento.min = dataAtual();

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
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Salvando...';
        });

        validarTitulo(false);
        validarData(false);
    });
</script>