<main class="container my-4 my-lg-5">
    <style>
        .card-noticia {
            border-radius: 14px;
            overflow: hidden
        }

        .card-noticia .form-control {
            border-radius: 8px
        }

        .card-noticia textarea {
            min-height: 220px;
            resize: vertical
        }

        .area-preview {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 12px
        }

        .area-preview img {
            width: 100%;
            max-height: 380px;
            object-fit: contain;
            border-radius: 8px;
            background: #e9ecef
        }

        .contador {
            font-size: .8rem;
            color: #6c757d
        }

        @media(max-width:576px) {
            .card-noticia {
                border-radius: 10px
            }

            .area-preview img {
                max-height: 250px
            }

            .botoes-formulario .btn {
                width: 100%
            }
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card card-noticia shadow-sm border-0">
                <div class="card-header bg-primary text-white p-3 p-md-4">
                    <h1 class="h4 mb-1 fw-bold"><i class="bi bi-newspaper me-2"></i>Nova Notícia</h1>
                    <p class="mb-0 small opacity-75">Preencha as informações para publicar uma notícia no portal.</p>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form id="formCadastroNoticia" action="/cadastro/noticia" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">Título da Notícia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" minlength="5" maxlength="150" placeholder="Digite o título da notícia" aria-describedby="erroTitulo contadorTitulo" required>
                            <div class="invalid-feedback" id="erroTitulo"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Entre 5 e 150 caracteres.</small>
                                <small class="contador" id="contadorTitulo">0/150</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="subtitulo" class="form-label fw-semibold">Subtítulo / Resumo <span class="text-muted fw-normal">(opcional)</span></label>
                            <input type="text" class="form-control" id="subtitulo" name="subtitulo" maxlength="255" placeholder="Escreva um pequeno resumo" aria-describedby="erroSubtitulo contadorSubtitulo">
                            <div class="invalid-feedback" id="erroSubtitulo"></div>
                            <div class="text-end mt-1">
                                <small class="contador" id="contadorSubtitulo">0/255</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="texto" class="form-label fw-semibold">Conteúdo Principal <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="texto" name="descricao" rows="9" minlength="30" placeholder="Escreva aqui o conteúdo completo da notícia..." aria-describedby="erroTexto contadorTexto" required></textarea>
                            <div class="invalid-feedback" id="erroTexto"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Mínimo de 30 caracteres.</small>
                                <small class="contador" id="contadorTexto">0 caracteres</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="img" class="form-label fw-semibold">Imagem de Capa <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="img" name="img" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-describedby="erroImg descricaoImg" required>
                            <div class="invalid-feedback" id="erroImg"></div>
                            <div class="form-text" id="descricaoImg">Formatos: JPG, PNG ou WebP. Máximo de 10 MB e 16 megapixels.</div>
                            <div id="statusImg" class="small mt-2" aria-live="polite"></div>
                            <div id="previewCapa" class="area-preview mt-3 d-none">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold small"><i class="bi bi-image me-1"></i>Pré-visualização</span>
                                    <span class="badge bg-success">Imagem válida</span>
                                </div>
                                <img id="imagemPreview" alt="Pré-visualização da imagem de capa">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-3">
                                    <div class="small">
                                        <div id="nomeImagem" class="fw-semibold text-break"></div>
                                        <div id="infoImagem" class="text-muted"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removerImagem"><i class="bi bi-trash me-1"></i>Remover</button>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 botoes-formulario">
                            <a href="/painel/noticia" class="btn btn-outline-secondary px-4"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSalvar"><i class="bi bi-check-circle me-1"></i>Salvar Notícia</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formCadastroNoticia');
        const titulo = document.getElementById('titulo');
        const subtitulo = document.getElementById('subtitulo');
        const texto = document.getElementById('texto');
        const img = document.getElementById('img');
        const preview = document.getElementById('previewCapa');
        const imagemPreview = document.getElementById('imagemPreview');
        const nomeImagem = document.getElementById('nomeImagem');
        const infoImagem = document.getElementById('infoImagem');
        const statusImg = document.getElementById('statusImg');
        const btnSalvar = document.getElementById('btnSalvar');

        const MAX_BYTES = 10 * 1024 * 1024;
        const MAX_PIXELS = 16000000;
        const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];

        let urlPreview = null;
        let arquivoValidado = null;
        let validacaoImagem = Promise.resolve(false);
        let verificacaoAtual = 0;
        let validandoEnvio = false;
        let enviando = false;

        function atualizarContadores() {
            document.getElementById('contadorTitulo').textContent = `${titulo.value.length}/150`;
            document.getElementById('contadorSubtitulo').textContent = `${subtitulo.value.length}/255`;
            document.getElementById('contadorTexto').textContent = `${texto.value.trim().length} caracteres`;
        }

        function validarCampo(campo, mostrar = true) {
            const valor = campo.value.trim();
            let mensagem = '';

            if (campo.id === 'titulo') {
                if (!valor) mensagem = 'Informe o título da notícia.';
                else if (valor.length < 5) mensagem = 'O título precisa ter pelo menos 5 caracteres.';
                else if (valor.length > 150) mensagem = 'O título pode ter no máximo 150 caracteres.';
            }

            if (campo.id === 'subtitulo' && valor.length > 255) {
                mensagem = 'O subtítulo pode ter no máximo 255 caracteres.';
            }

            if (campo.id === 'texto') {
                if (!valor) mensagem = 'Informe o conteúdo da notícia.';
                else if (valor.length < 30) mensagem = 'O conteúdo precisa ter pelo menos 30 caracteres.';
            }

            const valido = mensagem === '';
            const exibir = mostrar || campo.dataset.tocado === 'true';
            campo.classList.toggle('is-valid', exibir && valido);
            campo.classList.toggle('is-invalid', exibir && !valido);

            if (exibir) campo.setAttribute('aria-invalid', String(!valido));
            else campo.removeAttribute('aria-invalid');

            const erros = {
                titulo: 'erroTitulo',
                subtitulo: 'erroSubtitulo',
                texto: 'erroTexto'
            };
            document.getElementById(erros[campo.id]).textContent = mensagem;
            return valido;
        }

        [titulo, subtitulo, texto].forEach(campo => {
            campo.addEventListener('input', () => {
                campo.dataset.tocado = 'true';
                validarCampo(campo);
                atualizarContadores();
            });
            campo.addEventListener('blur', () => {
                campo.value = campo.value.trim();
                campo.dataset.tocado = 'true';
                validarCampo(campo);
                atualizarContadores();
            });
        });

        function estadoImagem(valido, mensagem = '') {
            img.classList.toggle('is-valid', valido === true);
            img.classList.toggle('is-invalid', valido === false);
            document.getElementById('erroImg').textContent = mensagem;
            if (valido === null) img.removeAttribute('aria-invalid');
            else img.setAttribute('aria-invalid', String(!valido));
        }

        function limparPreview() {
            if (urlPreview) URL.revokeObjectURL(urlPreview);
            urlPreview = null;
            arquivoValidado = null;
            imagemPreview.removeAttribute('src');
            preview.classList.add('d-none');
            nomeImagem.textContent = '';
            infoImagem.textContent = '';
        }

        async function validarImagem() {
            const verificacao = ++verificacaoAtual;
            const arquivo = img.files[0];

            limparPreview();
            estadoImagem(null);
            statusImg.textContent = '';

            if (!arquivo) {
                estadoImagem(false, 'Selecione uma imagem de capa.');
                return false;
            }
            if (!tiposPermitidos.includes(arquivo.type)) {
                estadoImagem(false, 'Formato inválido. Utilize JPG, PNG ou WebP.');
                return false;
            }
            if (arquivo.size === 0 || arquivo.size > MAX_BYTES) {
                estadoImagem(false, arquivo.size === 0 ? 'O arquivo está vazio.' : 'A imagem ultrapassa 10 MB.');
                return false;
            }

            statusImg.className = 'small mt-2 text-secondary';
            statusImg.textContent = 'Verificando imagem...';
            const url = URL.createObjectURL(arquivo);

            try {
                const dimensoes = await new Promise((resolve, reject) => {
                    const teste = new Image();
                    teste.onload = () => resolve({
                        largura: teste.naturalWidth,
                        altura: teste.naturalHeight
                    });
                    teste.onerror = () => reject(new Error('Imagem inválida.'));
                    teste.src = url;
                });

                if (verificacao !== verificacaoAtual) {
                    URL.revokeObjectURL(url);
                    return false;
                }

                const {
                    largura,
                    altura
                } = dimensoes;

                if (!largura || !altura || largura * altura > MAX_PIXELS) {
                    URL.revokeObjectURL(url);
                    estadoImagem(false, 'A imagem possui dimensões inválidas ou ultrapassa 16 megapixels.');
                    statusImg.textContent = '';
                    return false;
                }

                urlPreview = url;
                arquivoValidado = arquivo;
                imagemPreview.src = url;
                nomeImagem.textContent = arquivo.name;
                infoImagem.textContent = `${largura} × ${altura} px • ${(arquivo.size / 1048576).toFixed(2).replace('.', ',')} MB`;
                preview.classList.remove('d-none');
                estadoImagem(true);
                statusImg.className = 'small mt-2 text-success';
                statusImg.textContent = 'Imagem pronta para envio.';
                return true;

            } catch (erro) {
                URL.revokeObjectURL(url);
                if (verificacao === verificacaoAtual) {
                    estadoImagem(false, 'Não foi possível reconhecer a imagem.');
                    statusImg.textContent = '';
                }
                return false;
            }
        }

        img.addEventListener('change', () => {
            validacaoImagem = validarImagem();
        });

        document.getElementById('removerImagem').addEventListener('click', () => {
            ++verificacaoAtual;
            img.value = '';
            limparPreview();
            validacaoImagem = Promise.resolve(false);
            estadoImagem(false, 'Selecione uma imagem de capa.');
            statusImg.textContent = '';
            img.focus();
        });

        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (validandoEnvio || enviando) return;
            validandoEnvio = true;

            titulo.value = titulo.value.trim();
            subtitulo.value = subtitulo.value.trim();
            texto.value = texto.value.trim();
            atualizarContadores();

            const tituloValido = validarCampo(titulo);
            const subtituloValido = validarCampo(subtitulo);
            const textoValido = validarCampo(texto);
            let imagemValida = false;

            if (!img.files.length) {
                estadoImagem(false, 'Selecione uma imagem de capa.');
            } else {
                imagemValida = await validacaoImagem;
                imagemValida = imagemValida && arquivoValidado === img.files[0];
                if (!imagemValida) estadoImagem(false, 'Selecione uma imagem válida.');
            }

            if (!tituloValido || !subtituloValido || !textoValido || !imagemValida) {
                validandoEnvio = false;
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
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Salvando notícia...';
            form.submit();
        });

        atualizarContadores();
        window.addEventListener('pagehide', () => {
            if (urlPreview) URL.revokeObjectURL(urlPreview);
        });
    });
</script>