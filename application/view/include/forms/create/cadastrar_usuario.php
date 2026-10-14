<?php
$series = $model['series'] ?? [];
if (!is_iterable($series)) $series = [];
$acesso = (int)($_SESSION['acesso'] ?? 0);
$esc = fn($valor): string => htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<main class="container py-4 py-md-5">
    <style>
        .cadastro-container {
            max-width: 950px;
            margin: auto
        }

        .cadastro-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden
        }

        .cadastro-header {
            background: #0d6efd;
            color: #fff;
            padding: 24px
        }

        .cadastro-body {
            padding: 30px
        }

        .secao-titulo {
            font-size: 1.05rem;
            font-weight: 600;
            padding-bottom: 10px;
            margin-bottom: 22px;
            border-bottom: 1px solid #dee2e6
        }

        .cadastro-card .form-control,
        .cadastro-card .form-select {
            min-height: 42px
        }

        .dados-especificos {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 22px
        }

        .dados-especificos[hidden],
        .campo-obrigatorio[hidden] {
            display: none !important
        }

        .campo-obrigatorio {
            color: #dc3545
        }

        .btn-cadastrar {
            min-width: 180px
        }

        @media(max-width:576px) {
            .cadastro-header {
                padding: 20px
            }

            .cadastro-body {
                padding: 20px 15px
            }

            .dados-especificos {
                padding: 15px
            }

            .cadastro-acoes {
                flex-direction: column-reverse
            }

            .cadastro-acoes .btn {
                width: 100%
            }
        }
    </style>
    <div class="cadastro-container">
        <div class="card cadastro-card shadow-sm">
            <div class="cadastro-header">
                <h1 class="h4 fw-bold mb-1"><i class="bi bi-person-plus-fill me-2"></i>Cadastro de Usuário</h1>
                <p class="mb-0 small opacity-75">Preencha os dados para cadastrar um novo usuário no sistema.</p>
            </div>
            <div class="cadastro-body">
                <form id="formCadastroUsuario" method="POST" action="" novalidate>
                    <h2 class="secao-titulo"><i class="bi bi-person-vcard me-2 text-primary"></i>Dados pessoais</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-7">
                            <label for="nome" class="form-label fw-semibold">Nome completo <span class="campo-obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome" minlength="3" maxlength="150" placeholder="Digite o nome completo" autocomplete="name" required>
                            <div class="invalid-feedback" id="erroNome"></div>
                            <div class="form-text">Apenas letras, acentos e espaços.</div>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="matricula" class="form-label fw-semibold">Matrícula <span class="campo-obrigatorio" id="matriculaObrigatoria" hidden>*</span></label>
                            <input type="text" class="form-control" id="matricula" name="matricula" inputmode="numeric" placeholder="Informe a matrícula">
                            <div class="invalid-feedback" id="erroMatricula"></div>
                            <div class="form-text" id="matriculaInfo">Apenas números.</div>
                        </div>
                    </div>
                    <h2 class="secao-titulo"><i class="bi bi-shield-lock me-2 text-primary"></i>Credenciais de acesso</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="usuario" class="form-label fw-semibold">Nome de usuário <span class="campo-obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="usuario" name="usuario" minlength="3" maxlength="100" placeholder="Digite o nome de usuário" autocomplete="username" required>
                            <div class="invalid-feedback" id="erroUsuario"></div>
                            <div class="form-text">Utilize apenas letras e números, sem espaços.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="senha" class="form-label fw-semibold">Senha <span class="campo-obrigatorio">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="senha" name="senha" minlength="8" placeholder="Digite uma senha" autocomplete="new-password" required>
                                <button type="button" class="btn btn-outline-secondary" data-toggle-password="senha" aria-label="Mostrar senha" aria-pressed="false"><i class="bi bi-eye"></i></button>
                            </div>
                            <div class="invalid-feedback" id="erroSenha"></div>
                            <div class="form-text">Utilize pelo menos 8 caracteres.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="confirmar_senha" class="form-label fw-semibold">Confirmar senha <span class="campo-obrigatorio">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha" autocomplete="new-password" required>
                                <button type="button" class="btn btn-outline-secondary" data-toggle-password="confirmar_senha" aria-label="Mostrar confirmação da senha" aria-pressed="false"><i class="bi bi-eye"></i></button>
                            </div>
                            <div class="invalid-feedback" id="erroConfirmarSenha"></div>
                        </div>
                    </div>
                    <h2 class="secao-titulo"><i class="bi bi-people me-2 text-primary"></i>Tipo de usuário</h2>
                    <div class="mb-4">
                        <label for="tipo_usuario" class="form-label fw-semibold">Selecione o tipo de usuário <span class="campo-obrigatorio">*</span></label>
                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                            <option value="" selected disabled>Selecione uma opção</option>
                            <?php if ($acesso >= 1): ?>
                                <option value="0">Aluno</option>
                            <?php endif; ?>
                            <?php if ($acesso >= 2): ?>
                                <option value="1">Professor</option>
                            <?php endif; ?>
                            <?php if ($acesso >= 3): ?>
                                <option value="2">Administração</option>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback" id="erroTipo"></div>
                    </div>
                    <div class="dados-especificos mb-4" id="dadosAluno" hidden>
                        <h2 class="secao-titulo"><i class="bi bi-mortarboard me-2 text-primary"></i>Dados escolares do aluno</h2>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="data_nascimento" class="form-label fw-semibold">Data de nascimento <span class="campo-obrigatorio">*</span></label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" disabled>
                                <div class="invalid-feedback" id="erroNascimento"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="id_serie" class="form-label fw-semibold">Série <span class="campo-obrigatorio">*</span></label>
                                <select class="form-select" id="id_serie" name="id_serie" disabled>
                                    <option value="" selected>Selecione a série</option>
                                    <?php foreach ($series as $serie): ?>
                                        <?php
                                        $serieId = is_array($serie) ? ($serie['id'] ?? '') : ($serie->id ?? '');
                                        $serieNome = is_array($serie) ? ($serie['nome'] ?? '') : ($serie->nome ?? '');
                                        ?>
                                        <option value="<?= $esc($serieId) ?>"><?= $esc($serieNome) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="erroSerie"></div>
                            </div>
                        </div>
                    </div>
                    <div class="dados-especificos mb-4" id="dadosAdministrador" hidden>
                        <h2 class="secao-titulo"><i class="bi bi-person-badge me-2 text-primary"></i>Dados administrativos</h2>
                        <div class="alert alert-primary mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <span id="descricaoAdministrador" aria-live="polite"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 cadastro-acoes">
                        <a href="/painel/gerenciamento" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
                        <button type="submit" class="btn btn-primary btn-cadastrar" id="btnCadastrar"><i class="bi bi-person-check me-1"></i>Cadastrar usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formCadastroUsuario');
        const campos = {
            nome: document.getElementById('nome'),
            matricula: document.getElementById('matricula'),
            usuario: document.getElementById('usuario'),
            senha: document.getElementById('senha'),
            confirmar_senha: document.getElementById('confirmar_senha'),
            tipo_usuario: document.getElementById('tipo_usuario'),
            data_nascimento: document.getElementById('data_nascimento'),
            id_serie: document.getElementById('id_serie')
        };
        const erros = {
            nome: 'erroNome',
            matricula: 'erroMatricula',
            usuario: 'erroUsuario',
            senha: 'erroSenha',
            confirmar_senha: 'erroConfirmarSenha',
            tipo_usuario: 'erroTipo',
            data_nascimento: 'erroNascimento',
            id_serie: 'erroSerie'
        };
        const filtros = {
            nome: valor => valor.replace(/[^\p{L}\p{M} ]/gu, ''),
            usuario: valor => valor.replace(/[^a-zA-Z0-9]/g, ''),
            matricula: valor => valor.replace(/[^0-9]/g, '')
        };
        const dadosAluno = document.getElementById('dadosAluno');
        const dadosAdministrador = document.getElementById('dadosAdministrador');
        const btnCadastrar = document.getElementById('btnCadastrar');
        let enviando = false;

        function hoje() {
            const data = new Date();
            const ano = data.getFullYear();
            const mes = String(data.getMonth() + 1).padStart(2, '0');
            const dia = String(data.getDate()).padStart(2, '0');
            return `${ano}-${mes}-${dia}`;
        }

        function atualizarCampos() {
            const tipo = campos.tipo_usuario.value;
            const aluno = tipo === '0';
            const administrador = ['1', '2'].includes(tipo);

            dadosAluno.hidden = !aluno;
            dadosAdministrador.hidden = !administrador;
            campos.data_nascimento.disabled = !aluno;
            campos.id_serie.disabled = !aluno;
            campos.data_nascimento.required = aluno;
            campos.id_serie.required = aluno;
            campos.matricula.required = aluno;

            document.getElementById('matriculaObrigatoria').hidden = !aluno;
            document.getElementById('matriculaInfo').textContent = aluno ?
                'A matrícula é obrigatória e aceita apenas números.' :
                administrador ?
                'Opcional. Caso informada, utilize apenas números.' :
                'Apenas números.';

            campos.matricula.placeholder = aluno ?
                'Informe a matrícula do aluno' :
                'Informe a matrícula (opcional)';

            document.getElementById('descricaoAdministrador').textContent =
                tipo === '1' ?
                'O usuário será cadastrado como Professor, com as permissões correspondentes ao seu nível de acesso.' :
                tipo === '2' ?
                'O usuário será cadastrado como membro da Administração, com as permissões correspondentes ao seu nível de acesso.' :
                '';

            [campos.data_nascimento, campos.id_serie].forEach(campo => {
                if (campo.disabled) {
                    campo.classList.remove('is-valid', 'is-invalid');
                    campo.removeAttribute('aria-invalid');
                    document.getElementById(erros[campo.id]).textContent = '';
                    document.getElementById(erros[campo.id]).classList.remove('d-block');
                }
            });

            validar(campos.matricula);
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
            if (campo.disabled) return atualizarEstado(campo, '', false);

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
                    if (campos.tipo_usuario.value === '0' && !valor) {
                        mensagem = 'A matrícula é obrigatória para alunos.';
                    } else if (valor && !/^[0-9]+$/.test(valor)) {
                        mensagem = 'A matrícula deve conter apenas números.';
                    }
                    break;

                case 'usuario':
                    if (!valor) mensagem = 'Informe o nome de usuário.';
                    else if (valor.length < 3) mensagem = 'O usuário precisa ter pelo menos 3 caracteres.';
                    else if (valor.length > 100) mensagem = 'O usuário pode ter no máximo 100 caracteres.';
                    else if (!/^[a-zA-Z0-9]+$/.test(valor)) mensagem = 'Utilize apenas letras e números, sem espaços.';
                    break;

                case 'senha':
                    if (!campo.value) mensagem = 'Informe uma senha.';
                    else if (campo.value.length < 8) mensagem = 'A senha precisa ter pelo menos 8 caracteres.';
                    break;

                case 'confirmar_senha':
                    if (!campo.value) mensagem = 'Confirme sua senha.';
                    else if (campo.value !== campos.senha.value) mensagem = 'As senhas não coincidem.';
                    break;

                case 'tipo_usuario':
                    if (!['0', '1', '2'].includes(campo.value) ||
                        !Array.from(campo.options).some(opcao =>
                            opcao.value === campo.value && !opcao.disabled
                        )) {
                        mensagem = 'Selecione um tipo de usuário válido.';
                    }
                    break;

                case 'data_nascimento':
                    if (!valor) {
                        mensagem = 'Informe a data de nascimento.';
                    } else {
                        const partes = valor.split('-').map(Number);
                        const [ano, mes, dia] = partes;
                        const data = new Date(0);
                        data.setFullYear(ano, mes - 1, dia);

                        if (!/^\d{4}-\d{2}-\d{2}$/.test(valor) ||
                            data.getFullYear() !== ano ||
                            data.getMonth() !== mes - 1 ||
                            data.getDate() !== dia) {
                            mensagem = 'Informe uma data válida.';
                        } else if (valor > hoje()) {
                            mensagem = 'A data de nascimento não pode ser futura.';
                        }
                    }
                    break;

                case 'id_serie':
                    if (!valor || !Array.from(campo.options).some(opcao =>
                            opcao.value === valor && !opcao.disabled
                        )) {
                        mensagem = 'Selecione uma série válida.';
                    }
                    break;
            }

            return atualizarEstado(campo, mensagem, mostrar);
        }

        Object.values(campos).forEach(campo => {
            campo.addEventListener('input', () => {
                if (filtros[campo.id]) {
                    campo.value = filtros[campo.id](campo.value);
                }

                campo.dataset.tocado = 'true';

                if (campo === campos.tipo_usuario) atualizarCampos();

                validar(campo);

                if (campo === campos.senha &&
                    campos.confirmar_senha.dataset.tocado === 'true') {
                    validar(campos.confirmar_senha);
                }
            });

            campo.addEventListener('blur', () => {
                if (['nome', 'usuario', 'matricula'].includes(campo.id)) {
                    campo.value = campo.value.trim();
                }

                campo.dataset.tocado = 'true';
                validar(campo);
            });
        });

        campos.tipo_usuario.addEventListener('change', () => {
            atualizarCampos();
            campos.tipo_usuario.dataset.tocado = 'true';
            validar(campos.tipo_usuario);
        });

        document.querySelectorAll('[data-toggle-password]').forEach(botao => {
            botao.addEventListener('click', () => {
                const input = document.getElementById(botao.dataset.togglePassword);
                const mostrar = input.type === 'password';

                input.type = mostrar ? 'text' : 'password';
                botao.querySelector('i').className = mostrar ? 'bi bi-eye-slash' : 'bi bi-eye';
                botao.setAttribute('aria-pressed', String(mostrar));
                botao.setAttribute('aria-label', mostrar ? 'Ocultar senha' : 'Mostrar senha');
            });
        });

        form.addEventListener('submit', event => {
            if (enviando) {
                event.preventDefault();
                return;
            }

            ['nome', 'matricula', 'usuario'].forEach(id => {
                campos[id].value = campos[id].value.trim();
            });

            campos.data_nascimento.max = hoje();

            let primeiroErro = null;

            Object.values(campos).forEach(campo => {
                if (!validar(campo, true) && !primeiroErro) {
                    primeiroErro = campo;
                }
            });

            if (primeiroErro) {
                event.preventDefault();
                primeiroErro.focus();
                primeiroErro.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            enviando = true;
            btnCadastrar.disabled = true;
            btnCadastrar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Cadastrando...';
        });

        campos.data_nascimento.max = hoje();
        atualizarCampos();
    });
</script>