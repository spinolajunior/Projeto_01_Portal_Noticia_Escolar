<?php
$aluno = $model['aluno'] ?? null;
$credenciais = $model['credenciais'] ?? null;
$series = $model['series'] ?? [];
if (!is_iterable($series)) $series = [];
$fotoAluno = trim((string)($credenciais->foto ?? ''));
$foto = $fotoAluno !== '' ? '/' . ltrim(str_replace('\\', '/', $fotoAluno), '/') : '/view/img/upload/perfil_usuario/user_def.jpg';
$esc = static fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<main class="container py-4 py-md-5">
    <style>
        .edicao-container {
            max-width: 950px;
            margin: auto
        }

        .edicao-card {
            border: 0;
            border-radius: 14px;
            overflow: hidden
        }

        .edicao-header {
            background: #0d6efd;
            color: #fff;
            padding: 24px
        }

        .edicao-body {
            padding: 30px
        }

        .secao-titulo {
            font-size: 1.05rem;
            font-weight: 600;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6
        }

        .edicao-card .form-control,
        .edicao-card .form-select {
            min-height: 42px;
            border-radius: 7px
        }

        .campo-obrigatorio {
            color: #dc3545
        }

        .foto-perfil-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 1.25rem;
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 12px
        }

        .foto-preview-container {
            flex-shrink: 0;
            text-align: center
        }

        .foto-preview {
            width: 145px;
            height: 145px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            background: #fff;
            box-shadow: 0 .2rem .7rem rgba(0, 0, 0, .12)
        }

        .foto-campos {
            flex: 1;
            min-width: 0
        }

        @media(max-width:576px) {
            .edicao-header {
                padding: 20px
            }

            .edicao-body {
                padding: 20px 15px
            }

            .foto-perfil-container {
                flex-direction: column;
                text-align: center;
                gap: 1.2rem
            }

            .foto-preview {
                width: 125px;
                height: 125px
            }

            .foto-campos {
                width: 100%;
                text-align: left
            }

            .acoes-edicao {
                flex-direction: column-reverse
            }

            .acoes-edicao .btn {
                width: 100%
            }
        }
    </style>
    <div class="edicao-container">
        <div class="card shadow-sm edicao-card">
            <div class="edicao-header">
                <h1 class="h4 fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Editar Aluno</h1>
                <p class="small opacity-75 mb-0">Atualize os dados do aluno cadastrado.</p>
            </div>
            <div class="edicao-body">
                <form method="POST" enctype="multipart/form-data" id="formEditarAluno" novalidate>
                    <input type="hidden" name="id" value="<?= (int)($aluno->id ?? 0) ?>">
                    <input type="hidden" name="id_credencial" value="<?= (int)($credenciais->id ?? 0) ?>">
                    <h2 class="secao-titulo"><i class="bi bi-image me-2 text-primary"></i>Foto do perfil</h2>
                    <div class="foto-perfil-container mb-4">
                        <div class="foto-preview-container">
                            <img src="<?= $esc($foto) ?>" alt="Foto atual do aluno" id="previewFoto" class="foto-preview">
                        </div>
                        <div class="foto-campos">
                            <label for="foto" class="form-label fw-semibold">Alterar foto <span class="text-muted fw-normal">(opcional)</span></label>
                            <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-describedby="erroFoto">
                            <div class="invalid-feedback" id="erroFoto"></div>
                            <input type="hidden" name="foto_atual" value="<?= $esc($fotoAluno) ?>">
                            <div class="form-text">JPG, PNG ou WebP. Máximo de 10 MB e 16 megapixels. Se não selecionar outra imagem, a atual será mantida.</div>
                            <div id="infoFoto" class="small mt-2 text-muted" aria-live="polite">Foto atual cadastrada.</div>
                            <button type="button" id="removerFoto" class="btn btn-sm btn-outline-danger mt-2 d-none"><i class="bi bi-arrow-counterclockwise me-1"></i>Descartar nova foto</button>
                        </div>
                    </div>
                    <h2 class="secao-titulo"><i class="bi bi-person-vcard me-2 text-primary"></i>Dados pessoais</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-7">
                            <label for="nome" class="form-label fw-semibold">Nome completo <span class="campo-obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $esc($aluno->nome ?? '') ?>" minlength="3" maxlength="150" placeholder="Digite o nome completo" autocomplete="name" required>
                            <div class="invalid-feedback" id="erroNome"></div>
                            <div class="form-text">Apenas letras, acentos e espaços.</div>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="matricula" class="form-label fw-semibold">Matrícula <span class="campo-obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="matricula" name="matricula" value="<?= $esc($aluno->matricula ?? '') ?>" inputmode="numeric" placeholder="Matrícula do aluno" required>
                            <div class="invalid-feedback" id="erroMatricula"></div>
                            <div class="form-text">Utilize apenas números.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="data_nascimento" class="form-label fw-semibold">Data de nascimento <span class="campo-obrigatorio">*</span></label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?= $esc($aluno->data_nascimento ?? '') ?>" required>
                            <div class="invalid-feedback" id="erroNascimento"></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="id_serie" class="form-label fw-semibold">Série <span class="campo-obrigatorio">*</span></label>
                            <select class="form-select" id="id_serie" name="id_serie" required>
                                <option value="">Selecione a série</option>
                                <?php foreach ($series as $serie): ?>
                                    <?php
                                    $serieId = is_array($serie) ? ($serie['id'] ?? 0) : ($serie->id ?? 0);
                                    $serieNome = is_array($serie) ? ($serie['nome'] ?? '') : ($serie->nome ?? '');
                                    ?>
                                    <option value="<?= (int)$serieId ?>" <?= (int)($aluno->id_serie ?? 0) === (int)$serieId ? 'selected' : '' ?>><?= $esc($serieNome) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="erroSerie"></div>
                        </div>
                    </div>
                    <h2 class="secao-titulo"><i class="bi bi-shield-lock me-2 text-primary"></i>Credenciais</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="usuario" class="form-label fw-semibold">Nome de usuário <span class="campo-obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $esc($credenciais->usuario ?? '') ?>" minlength="3" maxlength="100" autocomplete="username" placeholder="Digite o nome de usuário" required>
                            <div class="invalid-feedback" id="erroUsuario"></div>
                            <div class="form-text">Entre 3 e 100 caracteres, apenas letras e números, sem espaços.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="senha" class="form-label fw-semibold">Nova senha <span class="text-muted fw-normal">(opcional)</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="senha" name="senha" minlength="8" autocomplete="new-password" placeholder="Deixe vazio para manter a atual" aria-describedby="erroSenha">
                                <button type="button" class="btn btn-outline-secondary" data-toggle-password="senha" aria-label="Mostrar senha" aria-pressed="false"><i class="bi bi-eye"></i></button>
                            </div>
                            <div class="invalid-feedback" id="erroSenha"></div>
                            <div class="form-text">Mínimo de 8 caracteres se desejar alterar a senha.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="confirmar_senha" class="form-label fw-semibold">Confirmar nova senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" minlength="8" autocomplete="new-password" placeholder="Repita a nova senha" aria-describedby="erroConfirmarSenha">
                                <button type="button" class="btn btn-outline-secondary" data-toggle-password="confirmar_senha" aria-label="Mostrar confirmação da senha" aria-pressed="false"><i class="bi bi-eye"></i></button>
                            </div>
                            <div class="invalid-feedback" id="erroConfirmarSenha"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 acoes-edicao">
                        <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSalvar"><i class="bi bi-check-lg me-1"></i>Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formEditarAluno');
        const campos = {
            nome: document.getElementById('nome'),
            matricula: document.getElementById('matricula'),
            data_nascimento: document.getElementById('data_nascimento'),
            id_serie: document.getElementById('id_serie'),
            usuario: document.getElementById('usuario'),
            senha: document.getElementById('senha'),
            confirmar_senha: document.getElementById('confirmar_senha')
        };
        const erros = {
            nome: 'erroNome',
            matricula: 'erroMatricula',
            data_nascimento: 'erroNascimento',
            id_serie: 'erroSerie',
            usuario: 'erroUsuario',
            senha: 'erroSenha',
            confirmar_senha: 'erroConfirmarSenha'
        };
        const filtros = {
            nome: valor => valor.replace(/[^\p{L}\p{M} ]/gu, ''),
            matricula: valor => valor.replace(/[^0-9]/g, ''),
            usuario: valor => valor.replace(/[^a-zA-Z0-9]/g, '')
        };
        const foto = document.getElementById('foto');
        const previewFoto = document.getElementById('previewFoto');
        const infoFoto = document.getElementById('infoFoto');
        const removerFoto = document.getElementById('removerFoto');
        const btnSalvar = document.getElementById('btnSalvar');
        const fotoOriginal = previewFoto.getAttribute('src');
        const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        const MAX_BYTES = 10 * 1024 * 1024;
        const MAX_PIXELS = 16000000;
        let urlFoto = null;
        let arquivoValidado = null;
        let versaoFoto = 0;
        let validacaoFoto = Promise.resolve(true);
        let processando = false;
        let enviando = false;

        function hoje() {
            const data = new Date();
            const ano = data.getFullYear();
            const mes = String(data.getMonth() + 1).padStart(2, '0');
            const dia = String(data.getDate()).padStart(2, '0');
            return `${ano}-${mes}-${dia}`;
        }

        function atualizarEstado(campo, mensagem, mostrar) {
            const valido = mensagem === '';
            const preenchido = campo.value.trim() !== '';
            campo.classList.toggle('is-valid', mostrar && valido && preenchido);
            campo.classList.toggle('is-invalid', mostrar && !valido);

            if (mostrar) campo.setAttribute('aria-invalid', String(!valido));
            else campo.removeAttribute('aria-invalid');

            const erro = document.getElementById(erros[campo.id]);
            erro.textContent = mensagem;
            erro.classList.toggle('d-block', mostrar && !valido);

            return valido;
        }

        function validar(campo, forcar = false) {
            const valor = campo.value.trim();
            const mostrar = forcar || campo.dataset.tocado === 'true';
            let mensagem = '';

            switch (campo.id) {
                case 'nome':
                    if (!valor) mensagem = 'Informe o nome completo.';
                    else if (valor.length < 3) mensagem = 'O nome precisa ter pelo menos 3 caracteres.';
                    else if (valor.length > 150) mensagem = 'O nome pode ter no máximo 150 caracteres.';
                    else if (!/^[\p{L}\p{M} ]+$/u.test(valor)) mensagem = 'Utilize apenas letras, acentos e espaços.';
                    break;

                case 'matricula':
                    if (!valor) mensagem = 'Informe a matrícula do aluno.';
                    else if (!/^[0-9]+$/.test(valor)) mensagem = 'A matrícula deve conter apenas números.';
                    break;

                case 'data_nascimento':
                    if (!valor) {
                        mensagem = 'Informe a data de nascimento.';
                    } else if (!/^\d{4}-\d{2}-\d{2}$/.test(valor)) {
                        mensagem = 'Informe uma data válida.';
                    } else {
                        const [ano, mes, dia] = valor.split('-').map(Number);
                        const data = new Date(0);
                        data.setFullYear(ano, mes - 1, dia);

                        if (data.getFullYear() !== ano || data.getMonth() !== mes - 1 || data.getDate() !== dia) {
                            mensagem = 'A data de nascimento informada não existe.';
                        } else if (valor > hoje()) {
                            mensagem = 'A data de nascimento não pode ser futura.';
                        }
                    }
                    break;

                case 'id_serie':
                    if (!valor || !Array.from(campo.options).some(opcao => opcao.value === valor && !opcao.disabled && valor !== '0')) {
                        mensagem = 'Selecione uma série válida.';
                    }
                    break;

                case 'usuario':
                    if (!valor) mensagem = 'Informe o nome de usuário.';
                    else if (valor.length < 3) mensagem = 'O usuário precisa ter pelo menos 3 caracteres.';
                    else if (valor.length > 100) mensagem = 'O usuário pode ter no máximo 100 caracteres.';
                    else if (!/^[a-zA-Z0-9]+$/.test(valor)) mensagem = 'Utilize apenas letras e números, sem espaços.';
                    break;

                case 'senha':
                    if (campo.value !== '' || campos.confirmar_senha.value !== '') {
                        if (!campo.value) mensagem = 'Informe a nova senha.';
                        else if (campo.value.trim() === '') mensagem = 'A senha não pode conter apenas espaços.';
                        else if (campo.value.length < 8) mensagem = 'A senha precisa ter pelo menos 8 caracteres.';
                    }
                    break;

                case 'confirmar_senha':
                    if (campos.senha.value !== '' || campo.value !== '') {
                        if (!campo.value) mensagem = 'Confirme a nova senha.';
                        else if (campo.value !== campos.senha.value) mensagem = 'As senhas informadas não coincidem.';
                    }
                    break;
            }

            return atualizarEstado(campo, mensagem, mostrar);
        }

        Object.values(campos).forEach(campo => {
            campo.addEventListener('input', () => {
                if (filtros[campo.id]) campo.value = filtros[campo.id](campo.value);
                campo.dataset.tocado = 'true';
                validar(campo);

                if (campo === campos.senha || campo === campos.confirmar_senha) {
                    const outro = campo === campos.senha ? campos.confirmar_senha : campos.senha;
                    if (outro.dataset.tocado === 'true') validar(outro);
                }
            });

            campo.addEventListener('change', () => {
                campo.dataset.tocado = 'true';
                validar(campo);
            });

            campo.addEventListener('blur', () => {
                if (['nome', 'matricula', 'usuario'].includes(campo.id)) campo.value = campo.value.trim();
                campo.dataset.tocado = 'true';
                validar(campo);
            });
        });

        document.querySelectorAll('[data-toggle-password]').forEach(botao => {
            botao.addEventListener('click', () => {
                const input = document.getElementById(botao.dataset.togglePassword);
                const mostrar = input.type === 'password';
                input.type = mostrar ? 'text' : 'password';
                botao.querySelector('i').className = mostrar ? 'bi bi-eye-slash' : 'bi bi-eye';
                botao.setAttribute('aria-label', mostrar ? 'Ocultar senha' : 'Mostrar senha');
                botao.setAttribute('aria-pressed', String(mostrar));
            });
        });

        function estadoFoto(valido, mensagem = '') {
            foto.classList.toggle('is-valid', valido === true);
            foto.classList.toggle('is-invalid', valido === false);

            const erro = document.getElementById('erroFoto');
            erro.textContent = mensagem;
            erro.classList.toggle('d-block', valido === false);

            if (valido === null) foto.removeAttribute('aria-invalid');
            else foto.setAttribute('aria-invalid', String(!valido));
        }

        function restaurarFoto() {
            if (urlFoto !== null) {
                URL.revokeObjectURL(urlFoto);
                urlFoto = null;
            }

            arquivoValidado = null;
            previewFoto.src = fotoOriginal;
            infoFoto.textContent = 'Foto atual cadastrada.';
            infoFoto.className = 'small mt-2 text-muted';
            removerFoto.classList.add('d-none');
        }

        async function validarFoto() {
            const versao = ++versaoFoto;
            const arquivo = foto.files[0];

            restaurarFoto();
            estadoFoto(null);

            if (!arquivo) return true;

            removerFoto.classList.remove('d-none');

            if (!tiposPermitidos.includes(arquivo.type)) {
                estadoFoto(false, 'Formato inválido. Utilize JPG, PNG ou WebP.');
                return false;
            }

            if (arquivo.size === 0 || arquivo.size > MAX_BYTES) {
                estadoFoto(false, arquivo.size === 0 ? 'O arquivo está vazio.' : 'A imagem ultrapassa o limite de 10 MB.');
                return false;
            }

            infoFoto.textContent = 'Verificando imagem...';
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

                if (versao !== versaoFoto) {
                    URL.revokeObjectURL(url);
                    return false;
                }

                const {
                    largura,
                    altura
                } = dimensoes;

                if (!largura || !altura || largura * altura > MAX_PIXELS) {
                    URL.revokeObjectURL(url);
                    estadoFoto(false, 'A imagem possui dimensões inválidas ou ultrapassa 16 megapixels.');
                    infoFoto.textContent = 'Selecione outra imagem ou descarte a nova foto.';
                    return false;
                }

                urlFoto = url;
                arquivoValidado = arquivo;
                previewFoto.src = url;

                const tamanho = (arquivo.size / (1024 * 1024)).toFixed(2).replace('.', ',');
                infoFoto.textContent = `Nova foto: ${arquivo.name} | ${largura} × ${altura} px | ${tamanho} MB`;
                infoFoto.className = 'small mt-2 text-success';

                estadoFoto(true);
                return true;

            } catch (erro) {
                URL.revokeObjectURL(url);
                if (versao === versaoFoto) {
                    estadoFoto(false, 'Não foi possível reconhecer a imagem selecionada.');
                    infoFoto.textContent = 'Selecione outra imagem ou descarte a nova foto.';
                }
                return false;
            }
        }

        foto.addEventListener('change', () => {
            validacaoFoto = validarFoto();
        });

        removerFoto.addEventListener('click', () => {
            ++versaoFoto;
            foto.value = '';
            restaurarFoto();
            estadoFoto(null);
            validacaoFoto = Promise.resolve(true);
        });

        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (processando || enviando) return;
            processando = true;

            ['nome', 'matricula', 'usuario'].forEach(id => {
                campos[id].value = campos[id].value.trim();
            });

            campos.data_nascimento.max = hoje();
            let primeiroErro = null;

            Object.values(campos).forEach(campo => {
                if (!validar(campo, true) && !primeiroErro) primeiroErro = campo;
            });

            let fotoValida = await validacaoFoto;
            fotoValida = fotoValida && (!foto.files.length || arquivoValidado === foto.files[0]);

            if (!fotoValida) {
                estadoFoto(false, 'Selecione uma foto válida ou descarte a nova seleção.');
                if (!primeiroErro) primeiroErro = foto;
            }

            if (primeiroErro) {
                processando = false;
                primeiroErro.focus();
                primeiroErro.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            enviando = true;
            btnSalvar.disabled = true;
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Salvando alterações...';
            form.submit();
        });

        campos.data_nascimento.max = hoje();

        window.addEventListener('pagehide', () => {
            if (urlFoto !== null) URL.revokeObjectURL(urlFoto);
        });
    });
</script>