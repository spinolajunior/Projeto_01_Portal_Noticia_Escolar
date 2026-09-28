<?php

// Lista de séries recebida pelo Controller.
$series = $model["series"] ?? [];

if (!is_iterable($series)) {
    $series = [];
}

?>

<main class="container py-4 py-md-5">

    <style>
        .cadastro-container {
            max-width: 950px;
            margin: 0 auto;
        }

        .cadastro-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background-color: #fff;
        }

        .cadastro-header {
            background-color: #0d6efd;
            color: #fff;
            padding: 24px;
        }

        .cadastro-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cadastro-body {
            padding: 30px;
        }

        .secao-titulo {
            font-size: 1.05rem;
            font-weight: 600;
            color: #212529;
            padding-bottom: 10px;
            margin-bottom: 22px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border-radius: 7px;
            min-height: 42px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .15);
        }

        .campo-obrigatorio {
            color: #dc3545;
        }

        .dados-especificos {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 22px;
        }

        .dados-especificos[hidden],
        .campo-obrigatorio[hidden] {
            display: none !important;
        }

        .btn-cadastrar {
            min-width: 180px;
        }

        @media (max-width: 576px) {

            .cadastro-header {
                padding: 20px;
            }

            .cadastro-header h1 {
                font-size: 1.25rem;
            }

            .cadastro-body {
                padding: 20px 15px;
            }

            .dados-especificos {
                padding: 15px;
            }

            .cadastro-acoes {
                flex-direction: column-reverse;
            }

            .cadastro-acoes .btn {
                width: 100%;
            }
        }
    </style>


    <div class="cadastro-container">

        <div class="card cadastro-card shadow-sm">

            <!-- ====================================== -->
            <!-- CABEÇALHO -->
            <!-- ====================================== -->

            <div class="cadastro-header">

                <h1>
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Cadastro de Usuário
                </h1>

                <p class="mb-0 opacity-75">
                    Preencha os dados para cadastrar um novo usuário no sistema.
                </p>

            </div>


            <!-- ====================================== -->
            <!-- FORMULÁRIO -->
            <!-- ====================================== -->

            <div class="cadastro-body">

                <form
                    id="formCadastroUsuario"
                    method="POST"
                    action="">


                    <!-- ====================================== -->
                    <!-- DADOS PESSOAIS -->
                    <!-- ====================================== -->

                    <h2 class="secao-titulo">
                        <i class="bi bi-person-vcard me-2 text-primary"></i>
                        Dados pessoais
                    </h2>


                    <div class="row g-3 mb-4">

                        <!-- NOME -->

                        <div class="col-12 col-md-7">

                            <label for="nome" class="form-label">
                                Nome completo
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nome"
                                name="nome"
                                placeholder="Digite o nome completo"
                                maxlength="150"
                                autocomplete="name"
                                required>

                        </div>


                        <!-- MATRÍCULA -->

                        <div class="col-12 col-md-5">

                            <label for="matricula" class="form-label">

                                Matrícula

                                <span
                                    class="campo-obrigatorio"
                                    id="matriculaObrigatoria">*</span>

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="matricula"
                                name="matricula"
                                placeholder="Informe a matrícula">

                            <div
                                class="form-text d-none"
                                id="matriculaOpcional">

                                <i class="bi bi-info-circle me-1"></i>

                                A matrícula é opcional para professores
                                e administração.

                            </div>

                        </div>

                    </div>



                    <!-- ====================================== -->
                    <!-- CREDENCIAIS -->
                    <!-- ====================================== -->

                    <h2 class="secao-titulo">
                        <i class="bi bi-shield-lock me-2 text-primary"></i>
                        Credenciais de acesso
                    </h2>


                    <div class="row g-3 mb-4">

                        <!-- USUÁRIO -->

                        <div class="col-12">

                            <label for="usuario" class="form-label">
                                Nome de usuário
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="usuario"
                                name="usuario"
                                placeholder="Digite o nome de usuário"
                                autocomplete="username"
                                required>

                            <div class="form-text">
                                Este nome será utilizado para acessar o sistema.
                            </div>

                        </div>


                        <!-- SENHA -->

                        <div class="col-12 col-md-6">

                            <label for="senha" class="form-label">
                                Senha
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    class="form-control"
                                    id="senha"
                                    name="senha"
                                    placeholder="Digite uma senha"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required>

                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    data-toggle-password="senha"
                                    aria-label="Mostrar senha">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                            <div class="form-text">
                                Utilize pelo menos 8 caracteres.
                            </div>

                        </div>


                        <!-- CONFIRMAR SENHA -->

                        <div class="col-12 col-md-6">

                            <label for="confirmar_senha" class="form-label">
                                Confirmar senha
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirmar_senha"
                                    name="confirmar_senha"
                                    placeholder="Repita a senha"
                                    autocomplete="new-password"
                                    required>

                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    data-toggle-password="confirmar_senha"
                                    aria-label="Mostrar confirmação da senha">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                            <div
                                class="text-danger small mt-1 d-none"
                                id="erroSenha"
                                role="alert">
                                As senhas informadas não coincidem.
                            </div>

                        </div>

                    </div>



                    <!-- ====================================== -->
                    <!-- TIPO DE USUÁRIO -->
                    <!-- ====================================== -->

                    <h2 class="secao-titulo">
                        <i class="bi bi-people me-2 text-primary"></i>
                        Tipo de usuário
                    </h2>


                    <div class="row g-3 mb-4">

                        <div class="col-12">

                            <label for="tipo_usuario" class="form-label">
                                Selecione o tipo de usuário
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <select
                                class="form-select"
                                id="tipo_usuario"
                                name="tipo_usuario"
                                required>

                                <option value="" selected disabled>
                                    Selecione uma opção
                                </option>

                                <option value="0">
                                    Aluno
                                </option>

                                <option value="1">
                                    Professor
                                </option>

                                <option value="2">
                                    Administração
                                </option>

                            </select>

                        </div>

                    </div>



                    <!-- ====================================== -->
                    <!-- DADOS ESPECÍFICOS DO ALUNO -->
                    <!-- ====================================== -->

                    <div
                        class="dados-especificos mb-4"
                        id="dadosAluno"
                        hidden>

                        <h2 class="secao-titulo">

                            <i class="bi bi-mortarboard me-2 text-primary"></i>

                            Dados escolares do aluno

                        </h2>


                        <div class="row g-3">

                            <!-- DATA DE NASCIMENTO -->

                            <div class="col-12 col-md-6">

                                <label
                                    for="data_nascimento"
                                    class="form-label">

                                    Data de nascimento

                                    <span class="campo-obrigatorio">*</span>

                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="data_nascimento"
                                    name="data_nascimento"
                                    disabled>

                            </div>


                            <!-- SÉRIE -->

                            <div class="col-12 col-md-6">

                                <label for="id_serie" class="form-label">

                                    Série

                                    <span class="campo-obrigatorio">*</span>

                                </label>

                                <select
                                    class="form-select"
                                    id="id_serie"
                                    name="id_serie"
                                    disabled>

                                    <option value="" selected>
                                        Selecione a série
                                    </option>


                                    <?php foreach ($series as $serie): ?>

                                        <?php

                                        // Compatível com objetos e arrays.

                                        $serieId = is_array($serie)
                                            ? ($serie["id"] ?? "")
                                            : ($serie->id ?? "");

                                        $serieNome = is_array($serie)
                                            ? ($serie["nome"] ?? "")
                                            : ($serie->nome ?? "");

                                        ?>

                                        <option
                                            value="<?= htmlspecialchars((string) $serieId, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars((string) $serieNome, ENT_QUOTES, 'UTF-8') ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                    </div>



                    <!-- ====================================== -->
                    <!-- DADOS DO PROFESSOR / ADMINISTRAÇÃO -->
                    <!-- ====================================== -->

                    <div
                        class="dados-especificos mb-4"
                        id="dadosAdministrador"
                        hidden>

                        <h2 class="secao-titulo">

                            <i class="bi bi-person-badge me-2 text-primary"></i>

                            Dados administrativos

                        </h2>


                        <div class="alert alert-primary mb-0">

                            <i class="bi bi-info-circle me-2"></i>

                            <span
                                id="descricaoAdministrador"
                                aria-live="polite"></span>

                        </div>

                    </div>



                    <!-- ====================================== -->
                    <!-- AÇÕES -->
                    <!-- ====================================== -->

                    <div
                        class="d-flex justify-content-end gap-2 cadastro-acoes">

                        <a
                            href="/"
                            class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>
                            Voltar
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary btn-cadastrar">
                            <i class="bi bi-person-check me-1"></i>
                            Cadastrar usuário
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</main>



<!-- ====================================== -->
<!-- JAVASCRIPT -->
<!-- ====================================== -->

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // =========================================
        // ELEMENTOS DO FORMULÁRIO
        // =========================================

        const form = document.getElementById("formCadastroUsuario");

        const tipoUsuario = document.getElementById("tipo_usuario");

        const dadosAluno = document.getElementById("dadosAluno");

        const dadosAdministrador = document.getElementById("dadosAdministrador");

        const descricaoAdministrador = document.getElementById("descricaoAdministrador");

        const dataNascimento = document.getElementById("data_nascimento");

        const selectSerie = document.getElementById("id_serie");

        const matricula = document.getElementById("matricula");

        const matriculaObrigatoria = document.getElementById("matriculaObrigatoria");

        const matriculaOpcional = document.getElementById("matriculaOpcional");

        const senha = document.getElementById("senha");

        const confirmarSenha = document.getElementById("confirmar_senha");

        const erroSenha = document.getElementById("erroSenha");



        // =========================================
        // CONTROLE DOS CAMPOS CONFORME O TIPO
        // =========================================

        function atualizarCampos() {

            const tipo = tipoUsuario.value;

            // 0 = Aluno
            // 1 = Professor
            // 2 = Administração

            const alunoSelecionado = tipo === "0";

            const administradorSelecionado =
                tipo === "1" ||
                tipo === "2";


            // =====================================
            // CAMPOS DO ALUNO
            // =====================================

            // Exibe apenas quando o tipo for aluno.
            dadosAluno.hidden = !alunoSelecionado;

            // Habilita os campos somente para alunos.
            dataNascimento.disabled = !alunoSelecionado;

            selectSerie.disabled = !alunoSelecionado;

            // Define a obrigatoriedade.
            dataNascimento.required = alunoSelecionado;

            selectSerie.required = alunoSelecionado;



            // =====================================
            // MATRÍCULA
            // =====================================

            // Aluno: obrigatória.
            // Professor e administração: opcional.

            matricula.required = alunoSelecionado;


            // Controla o asterisco de obrigatoriedade.
            matriculaObrigatoria.hidden = !alunoSelecionado;


            // Mostra o texto informativo para administradores.
            matriculaOpcional.classList.toggle(
                "d-none",
                !administradorSelecionado
            );


            // Atualiza o placeholder.
            if (alunoSelecionado) {

                matricula.placeholder = "Informe a matrícula do aluno";

            } else if (administradorSelecionado) {

                matricula.placeholder = "Informe a matrícula (opcional)";

            } else {

                matricula.placeholder = "Informe a matrícula";

            }



            // =====================================
            // CAMPOS DO ADMINISTRADOR
            // =====================================

            // Exibe para professor e administração.
            dadosAdministrador.hidden = !administradorSelecionado;


            // Atualiza o texto conforme a seleção.
            switch (tipo) {

                case "1":

                    descricaoAdministrador.textContent =
                        "O usuário será cadastrado como Professor, com as permissões correspondentes ao seu nível de acesso.";

                    break;


                case "2":

                    descricaoAdministrador.textContent =
                        "O usuário será cadastrado como membro da Administração, com as permissões correspondentes ao seu nível de acesso.";

                    break;


                default:

                    descricaoAdministrador.textContent = "";

                    break;

            }

        }



        // =========================================
        // EVENTO DE ALTERAÇÃO DO TIPO
        // =========================================

        tipoUsuario.addEventListener("change", atualizarCampos);


        // Configura os campos ao carregar a página.
        atualizarCampos();



        // =========================================
        // MOSTRAR / OCULTAR SENHAS
        // =========================================

        document.querySelectorAll("[data-toggle-password]").forEach(function(botao) {

            botao.addEventListener("click", function() {

                const idInput = this.dataset.togglePassword;

                const input = document.getElementById(idInput);

                const icone = this.querySelector("i");


                if (input.type === "password") {

                    input.type = "text";

                    icone.classList.remove("bi-eye");

                    icone.classList.add("bi-eye-slash");

                    this.setAttribute("aria-label", "Ocultar senha");

                } else {

                    input.type = "password";

                    icone.classList.remove("bi-eye-slash");

                    icone.classList.add("bi-eye");

                    this.setAttribute("aria-label", "Mostrar senha");

                }

            });

        });



        // =========================================
        // VALIDAÇÃO DA CONFIRMAÇÃO DE SENHA
        // =========================================

        function validarSenhas() {

            if (
                confirmarSenha.value !== "" &&
                senha.value !== confirmarSenha.value
            ) {

                confirmarSenha.setCustomValidity(
                    "As senhas informadas não coincidem."
                );

                confirmarSenha.classList.add("is-invalid");

                erroSenha.classList.remove("d-none");

                return false;

            }


            // Remove os erros quando as senhas coincidirem.
            confirmarSenha.setCustomValidity("");

            confirmarSenha.classList.remove("is-invalid");

            erroSenha.classList.add("d-none");

            return true;

        }


        // Validação em tempo real.
        senha.addEventListener("input", validarSenhas);

        confirmarSenha.addEventListener("input", validarSenhas);



        // =========================================
        // VALIDAÇÃO ANTES DO ENVIO
        // =========================================

        form.addEventListener("submit", function(event) {

            // Confere se as senhas coincidem.
            validarSenhas();


            // Confere todos os campos obrigatórios.
            if (!form.checkValidity()) {

                event.preventDefault();

                form.reportValidity();

                return;

            }


            // Se estiver tudo correto, o formulário
            // será enviado normalmente via POST.
            //
            // O processamento será feito pelo PHP.

        });

    });
</script>