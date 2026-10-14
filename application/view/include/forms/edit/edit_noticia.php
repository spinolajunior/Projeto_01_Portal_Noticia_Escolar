<?php
$noticia = $model['noticia'];
$esc = static fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$imagemUrl = trim((string)($model['url_imagem'] ?? ''));
$raizProjeto = rtrim(defined('BASE_DIR') ? BASE_DIR : ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\');

if ($imagemUrl !== '') {
    $imagemUrl = '/' . ltrim(str_replace('\\', '/', $imagemUrl), '/');
    if (!preg_match('/\.(jpg|jpeg|png|webp)$/i', $imagemUrl)) $imagemUrl .= '.jpg';
    $caminhoFisico = $raizProjeto . $imagemUrl;
    if (!is_file($caminhoFisico) || !is_readable($caminhoFisico)) $imagemUrl = '';
}
?>
<main class="container py-4 py-lg-5 flex-grow-1">
    <style>
        .card-editar-noticia {
            border-radius: 14px;
            overflow: hidden
        }

        .card-editar-noticia .form-control,
        .card-editar-noticia .form-select {
            border-radius: 8px
        }

        .card-editar-noticia textarea {
            min-height: 220px;
            resize: vertical
        }

        .area-imagem-noticia {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 14px;
            text-align: center
        }

        .area-imagem-noticia img {
            width: 100%;
            max-height: 360px;
            object-fit: contain;
            border-radius: 8px
        }

        .contador-campo {
            font-size: .8rem;
            color: #6c757d
        }

        @media(max-width:576px) {
            .area-imagem-noticia img {
                max-height: 240px
            }

            .botoes-edicao .btn {
                width: 100%
            }
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card card-editar-noticia border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-3 p-md-4">
                    <h1 class="h4 mb-1 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Notícia</h1>
                    <p class="mb-0 small text-white-50">Atualize as informações da notícia selecionada.</p>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form id="formEditarNoticia" method="POST" action="/atualizar/noticia" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="id" value="<?= (int)($noticia->id ?? 0) ?>">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Imagem da notícia</label>
                            <div class="area-imagem-noticia">
                                <img id="previewImagem" data-original="<?= $esc($imagemUrl) ?>" <?= $imagemUrl !== '' ? 'src="' . $esc($imagemUrl) . '"' : '' ?> alt="Imagem da notícia" class="<?= $imagemUrl === '' ? 'd-none' : '' ?>">
                                <div id="semImagem" class="py-4 text-secondary <?= $imagemUrl !== '' ? 'd-none' : '' ?>">
                                    <i class="bi bi-image fs-1 d-block mb-2"></i>Nenhuma imagem cadastrada.
                                </div>
                            </div>
                            <div id="infoImagem" class="small text-muted mt-2" aria-live="polite">
                                <?= $imagemUrl !== '' ? 'Imagem atual cadastrada.' : 'Selecione uma imagem para a notícia.' ?>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="imagem" class="form-label fw-semibold">Substituir imagem <span class="text-muted fw-normal">(opcional se já existir uma imagem)</span></label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-describedby="erroImagem">
                            <div id="erroImagem" class="invalid-feedback"></div>
                            <div class="form-text">JPG, PNG ou WebP. Máximo de 10 MB e 16 megapixels. A imagem atual será mantida se nenhuma nova for selecionada.</div>
                            <button type="button" id="removerImagem" class="btn btn-sm btn-outline-danger mt-2 d-none"><i class="bi bi-arrow-counterclockwise me-1"></i>Descartar nova imagem</button>
                        </div>
                        <hr class="my-4">
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">Título da notícia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" minlength="5" maxlength="150" value="<?= $esc($noticia->titulo ?? '') ?>" placeholder="Digite o título da notícia" required>
                            <div id="erroTitulo" class="invalid-feedback"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Entre 5 e 150 caracteres.</small>
                                <span id="contadorTitulo" class="contador-campo">0/150</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="subtitulo" class="form-label fw-semibold">Subtítulo <span class="text-muted fw-normal">(opcional)</span></label>
                            <input type="text" class="form-control" id="subtitulo" name="subtitulo" maxlength="255" value="<?= $esc($noticia->subtitulo ?? '') ?>" placeholder="Digite o subtítulo da notícia">
                            <div id="erroSubtitulo" class="invalid-feedback"></div>
                            <div class="text-end mt-1"><span id="contadorSubtitulo" class="contador-campo">0/255</span></div>
                        </div>
                        <div class="mb-4">
                            <label for="descricao" class="form-label fw-semibold">Conteúdo da notícia <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="9" minlength="30" placeholder="Escreva o conteúdo da notícia" required><?= $esc($noticia->descricao ?? '') ?></textarea>
                            <div id="erroDescricao" class="invalid-feedback"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Mínimo de 30 caracteres.</small>
                                <span id="contadorDescricao" class="contador-campo">0 caracteres</span>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">Situação da notícia <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" <?= (int)($noticia->status ?? 0) === 1 ? 'selected' : '' ?>>Ativo</option>
                                    <option value="0" <?= (int)($noticia->status ?? 0) === 0 ? 'selected' : '' ?>>Desativada</option>
                                </select>
                                <div id="erroStatus" class="invalid-feedback">Selecione uma situação válida.</div>
                                <div class="form-text">Notícias desativadas não aparecem na página inicial.</div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 botoes-edicao">
                            <a href="/painel/noticia" class="btn btn-outline-secondary px-4"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
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
        const form = document.getElementById('formEditarNoticia');
        const titulo = document.getElementById('titulo');
        const subtitulo = document.getElementById('subtitulo');
        const descricao = document.getElementById('descricao');
        const status = document.getElementById('status');
        const imagem = document.getElementById('imagem');
        const preview = document.getElementById('previewImagem');
        const semImagem = document.getElementById('semImagem');
        const infoImagem = document.getElementById('infoImagem');
        const removerImagem = document.getElementById('removerImagem');
        const btnSalvar = document.getElementById('btnSalvar');
        const MAX_BYTES = 10 * 1024 * 1024;
        const MAX_PIXELS = 16000000;
        const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];

        let imagemOriginal = preview.dataset.original || '';
        let urlTemporaria = null;
        let arquivoValidado = null;
        let versaoImagem = 0;
        let validacaoPendente = Promise.resolve(true);
        let processando = false;
        let enviando = false;

        function atualizarContadores() {
            document.getElementById('contadorTitulo').textContent = `${titulo.value.length}/150`;
            document.getElementById('contadorSubtitulo').textContent = `${subtitulo.value.length}/255`;
            document.getElementById('contadorDescricao').textContent = `${descricao.value.trim().length} caracteres`;
        }

        function estadoCampo(campo, mensagem, mostrar) {
            const valido = mensagem === '';
            campo.classList.toggle('is-valid', mostrar && valido);
            campo.classList.toggle('is-invalid', mostrar && !valido);

            if (mostrar) campo.setAttribute('aria-invalid', String(!valido));
            else campo.removeAttribute('aria-invalid');

            document.getElementById('erro' + campo.id.charAt(0).toUpperCase() + campo.id.slice(1)).textContent = mensagem;
            return valido;
        }

        function validarCampo(campo, forcar = false) {
            const valor = campo.value.trim();
            const mostrar = forcar || campo.dataset.tocado === 'true';
            let mensagem = '';

            if (campo === titulo) {
                if (!valor) mensagem = 'Informe o título da notícia.';
                else if (valor.length < 5) mensagem = 'O título precisa ter pelo menos 5 caracteres.';
                else if (valor.length > 150) mensagem = 'O título pode ter no máximo 150 caracteres.';
            }

            if (campo === subtitulo && valor.length > 255) {
                mensagem = 'O subtítulo pode ter no máximo 255 caracteres.';
            }

            if (campo === descricao) {
                if (!valor) mensagem = 'Informe o conteúdo da notícia.';
                else if (valor.length < 30) mensagem = 'O conteúdo precisa ter pelo menos 30 caracteres.';
            }

            return estadoCampo(campo, mensagem, mostrar);
        }

        [titulo, subtitulo, descricao].forEach(campo => {
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

        function marcarImagem(valido, mensagem = '') {
            imagem.classList.toggle('is-valid', valido === true);
            imagem.classList.toggle('is-invalid', valido === false);
            if (valido === null) imagem.removeAttribute('aria-invalid');
            else imagem.setAttribute('aria-invalid', String(!valido));
            document.getElementById('erroImagem').textContent = mensagem;
        }

        function restaurarImagem() {
            if (urlTemporaria !== null) {
                URL.revokeObjectURL(urlTemporaria);
                urlTemporaria = null;
            }

            arquivoValidado = null;

            if (imagemOriginal !== '') {
                preview.src = imagemOriginal;
                preview.classList.remove('d-none');
                semImagem.classList.add('d-none');
                infoImagem.textContent = 'Imagem atual cadastrada.';
            } else {
                preview.removeAttribute('src');
                preview.classList.add('d-none');
                semImagem.classList.remove('d-none');
                infoImagem.textContent = 'Nenhuma imagem cadastrada. Selecione uma imagem.';
            }

            removerImagem.classList.add('d-none');
            infoImagem.className = 'small text-muted mt-2';
        }

        preview.addEventListener('error', () => {
            if (urlTemporaria === null) {
                imagemOriginal = '';
                preview.removeAttribute('src');
                preview.classList.add('d-none');
                semImagem.classList.remove('d-none');
                infoImagem.textContent = 'Imagem original indisponível. Selecione uma nova imagem.';
            }
        });

        async function validarImagem() {
            const versao = ++versaoImagem;
            const arquivo = imagem.files[0];

            restaurarImagem();
            marcarImagem(null);

            if (!arquivo) return imagemOriginal !== '';

            removerImagem.classList.remove('d-none');

            if (!tiposPermitidos.includes(arquivo.type)) {
                marcarImagem(false, 'Formato inválido. Utilize JPG, PNG ou WebP.');
                return false;
            }

            if (arquivo.size === 0 || arquivo.size > MAX_BYTES) {
                marcarImagem(false, arquivo.size === 0 ? 'O arquivo está vazio.' : 'A imagem ultrapassa 10 MB.');
                return false;
            }

            infoImagem.textContent = 'Verificando imagem...';
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

                if (versao !== versaoImagem) {
                    URL.revokeObjectURL(url);
                    return false;
                }

                const {
                    largura,
                    altura
                } = dimensoes;

                if (!largura || !altura || largura * altura > MAX_PIXELS) {
                    URL.revokeObjectURL(url);
                    marcarImagem(false, 'A imagem possui dimensões inválidas ou ultrapassa 16 megapixels.');
                    infoImagem.textContent = 'Selecione outra imagem ou descarte a nova seleção.';
                    return false;
                }

                urlTemporaria = url;
                arquivoValidado = arquivo;
                preview.src = url;
                preview.classList.remove('d-none');
                semImagem.classList.add('d-none');
                removerImagem.classList.remove('d-none');

                const tamanho = (arquivo.size / 1048576).toFixed(2).replace('.', ',');
                infoImagem.textContent = `Nova imagem: ${arquivo.name} | ${largura} × ${altura} px | ${tamanho} MB`;
                infoImagem.className = 'small text-success mt-2';
                marcarImagem(true);
                return true;

            } catch (erro) {
                URL.revokeObjectURL(url);
                if (versao === versaoImagem) {
                    marcarImagem(false, 'Não foi possível reconhecer a imagem.');
                    infoImagem.textContent = 'Selecione outra imagem ou descarte a nova seleção.';
                }
                return false;
            }
        }

        imagem.addEventListener('change', () => {
            validacaoPendente = validarImagem();
        });

        removerImagem.addEventListener('click', () => {
            ++versaoImagem;
            imagem.value = '';
            restaurarImagem();
            marcarImagem(null);
            validacaoPendente = Promise.resolve(imagemOriginal !== '');
        });

        function validarStatus() {
            const valido = ['0', '1'].includes(status.value);
            status.classList.toggle('is-valid', valido);
            status.classList.toggle('is-invalid', !valido);
            status.setAttribute('aria-invalid', String(!valido));
            return valido;
        }

        status.addEventListener('change', validarStatus);

        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (processando || enviando) return;
            processando = true;

            titulo.value = titulo.value.trim();
            subtitulo.value = subtitulo.value.trim();
            descricao.value = descricao.value.trim();
            atualizarContadores();

            const tituloValido = validarCampo(titulo, true);
            const subtituloValido = validarCampo(subtitulo, true);
            const descricaoValida = validarCampo(descricao, true);
            const statusValido = validarStatus();

            await validacaoPendente;
            const imagemValida = imagem.files.length ?
                arquivoValidado === imagem.files[0] :
                imagemOriginal !== '';

            if (!imagemValida) {
                marcarImagem(false, 'Selecione uma imagem válida.');
            }

            if (!tituloValido || !subtituloValido || !descricaoValida || !statusValido || !imagemValida) {
                processando = false;
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
            form.submit();
        });

        atualizarContadores();

        window.addEventListener('pagehide', () => {
            if (urlTemporaria !== null) URL.revokeObjectURL(urlTemporaria);
        });
    });
</script>