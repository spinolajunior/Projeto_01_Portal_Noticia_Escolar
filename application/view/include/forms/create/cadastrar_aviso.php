<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-3 p-md-4">
                    <h1 class="h4 card-title mb-1 fw-bold"><i class="bi bi-megaphone me-2"></i>Novo Aviso</h1>
                    <p class="mb-0 small text-white-50">Cadastre um novo aviso para o portal escolar.</p>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form id="formAviso" action="/cadastro/aviso" method="POST" novalidate>
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">Título do Aviso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" minlength="5" maxlength="50" placeholder="Digite o título do aviso" aria-describedby="erroTitulo contadorTitulo" required>
                            <div class="invalid-feedback" id="erroTitulo">Informe um título válido.</div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Entre 5 e 50 caracteres.</small>
                                <small class="text-muted" id="contadorTitulo">0/50</small>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="/painel/aviso" class="btn btn-outline-secondary px-4"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSalvar"><i class="bi bi-check-circle me-1"></i>Salvar Aviso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formAviso');
        const titulo = document.getElementById('titulo');
        const erro = document.getElementById('erroTitulo');
        const contador = document.getElementById('contadorTitulo');
        const btnSalvar = document.getElementById('btnSalvar');
        let enviando = false;

        function validar(mostrar = true) {
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
            titulo.setAttribute('aria-invalid', String(mostrar && !valido));
            erro.textContent = mensagem;
            contador.textContent = `${valor.length}/50`;
            return valido;
        }

        titulo.addEventListener('input', () => validar());

        titulo.addEventListener('blur', () => {
            titulo.value = titulo.value.trim();
            validar();
        });

        form.addEventListener('submit', (event) => {
            if (enviando) {
                event.preventDefault();
                return;
            }

            titulo.value = titulo.value.trim();

            if (!validar()) {
                event.preventDefault();
                titulo.focus();
                return;
            }

            enviando = true;
            btnSalvar.disabled = true;
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Salvando...';
        });

        validar(false);
    });
</script>