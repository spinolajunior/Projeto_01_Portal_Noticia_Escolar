<?php

$administrador = (object)$model["usuario"] ?? null;
$credenciais = (object)$model["credencial"] ?? null;


$fotoAdministrador = $credenciais->foto ?? null;

$foto = (
    $fotoAdministrador !== null &&
    $fotoAdministrador !== ""
)
    ? $fotoAdministrador
    : "/view/img/upload/perfil_usuario/user_def.jpg";

?>

<main class="container py-4">

    <style>
        .edicao-container {
            max-width: 950px;
            margin: 0 auto;
        }

        .edicao-card {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
        }

        .edicao-header {
            background-color: #0d6efd;
            color: #fff;
            padding: 24px;
        }

        .edicao-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .edicao-body {
            padding: 30px;
        }

        .secao-titulo {
            font-size: 1.05rem;
            font-weight: 600;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-label {
            font-weight: 500;
            font-size: .9rem;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            border-radius: 7px;
        }

        .campo-obrigatorio {
            color: #dc3545;
        }

        .foto-perfil-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 1.25rem;
            background-color: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: .8rem;
        }

        .foto-preview-container {
            flex-shrink: 0;
            text-align: center;
        }

        .foto-preview {
            width: 145px;
            height: 145px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            background-color: #fff;
            box-shadow: 0 .2rem .7rem rgba(0, 0, 0, .12);
        }

        .foto-campos {
            flex: 1;
            min-width: 0;
        }

        @media (max-width: 576px) {

            .edicao-header {
                padding: 20px;
            }

            .edicao-header h1 {
                font-size: 1.25rem;
            }

            .edicao-body {
                padding: 20px 15px;
            }

            .foto-perfil-container {
                flex-direction: column;
                text-align: center;
                gap: 1.2rem;
            }

            .foto-preview {
                width: 125px;
                height: 125px;
            }

            .foto-campos {
                width: 100%;
                text-align: left;
            }

            .acoes-edicao {
                flex-direction: column-reverse;
            }

            .acoes-edicao .btn {
                width: 100%;
            }
        }
    </style>

    <div class="edicao-container">

        <div class="card shadow-sm edicao-card">

            <div class="edicao-header">

                <h1>
                    <i class="bi bi-pencil-square me-2"></i>
                    Editar Administrador
                </h1>

                <div class="small opacity-75 mt-1">
                    Atualize os dados do administrador.
                </div>

            </div>

            <div class="edicao-body">

                <form
                    method="POST"
                    enctype="multipart/form-data"
                    id="formEditarAdministrador">


                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) ($administrador->id ?? 0) ?>">

                    <input
                        type="hidden"
                        name="id_credenciais"
                        value="<?= (int) ($credenciais->id ?? 0) ?>">

                    <h2 class="secao-titulo">
                        <i class="bi bi-image me-2 text-primary"></i>
                        Foto do perfil
                    </h2>

                    <div class="foto-perfil-container mb-4">

                        <div class="foto-preview-container">

                            <img
                                src="<?= htmlspecialchars(
                                            $foto,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                alt="Foto atual do administrador"
                                id="previewFoto"
                                class="foto-preview">

                        </div>

                        <div class="foto-campos">

                            <label
                                for="foto"
                                class="form-label">
                                Alterar foto
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="foto"
                                name="foto"
                                accept="image/jpeg,image/png,image/webp">

                            <input
                                type="hidden"
                                name="foto_atual"
                                value="<?= htmlspecialchars(
                                            $fotoAdministrador ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>">

                            <div class="form-text">
                                Selecione uma nova imagem apenas se desejar alterar a foto atual.
                                Formatos permitidos: JPG, PNG ou WEBP.
                            </div>

                        </div>

                    </div>

                    <h2 class="secao-titulo">
                        <i class="bi bi-person-vcard me-2 text-primary"></i>
                        Dados pessoais
                    </h2>

                    <div class="row g-3 mb-4">

                        <div class="col-12 col-md-7">

                            <label
                                for="nome"
                                class="form-label">
                                Nome completo
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nome"
                                name="nome"
                                value="<?= htmlspecialchars(
                                            $administrador->nome ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                required>

                        </div>

                        <div class="col-12 col-md-5">

                            <label
                                for="matricula"
                                class="form-label">
                                Matrícula
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="matricula"
                                name="matricula"
                                value="<?= htmlspecialchars(
                                            $administrador->matricula ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                placeholder="Matrícula do administrador">

                            <div class="form-text">
                                Matrícula opcional para administradores.
                            </div>

                        </div>



                    </div>

                    <h2 class="secao-titulo">
                        <i class="bi bi-shield-lock me-2 text-primary"></i>
                        Credenciais
                    </h2>

                    <div class="row g-3 mb-4">

                        <div class="col-12">

                            <label
                                for="usuario"
                                class="form-label">
                                Nome de usuário
                                <span class="campo-obrigatorio">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="usuario"
                                name="usuario"
                                value="<?= htmlspecialchars(
                                            $credenciais->usuario ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                required>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="senha"
                                class="form-label">
                                Nova senha
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    class="form-control"
                                    id="senha"
                                    name="senha"
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Deixe vazio para manter a atual">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    data-toggle-password="senha">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="confirmar_senha"
                                class="form-label">
                                Confirmar nova senha
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirmar_senha"
                                    name="confirmar_senha"
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Repita a nova senha">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    data-toggle-password="confirmar_senha">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                            <div
                                id="erroSenha"
                                class="text-danger small mt-1 d-none">
                                As senhas não coincidem.
                            </div>

                        </div>


                    </div>

                    <div class="d-flex justify-content-end gap-2 acoes-edicao">

                        <a
                            href="/usuarios"
                            class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>
                            Voltar
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>
                            Salvar alterações
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById(
            "formEditarAdministrador"
        );

        const senha = document.getElementById("senha");

        const confirmarSenha = document.getElementById(
            "confirmar_senha"
        );

        const erroSenha = document.getElementById(
            "erroSenha"
        );

        const inputFoto = document.getElementById("foto");

        const previewFoto = document.getElementById(
            "previewFoto"
        );

        document
            .querySelectorAll("[data-toggle-password]")
            .forEach(function(botao) {

                botao.addEventListener("click", function() {

                    const input = document.getElementById(
                        this.dataset.togglePassword
                    );

                    const icone = this.querySelector("i");

                    if (input.type === "password") {

                        input.type = "text";

                        icone.classList.remove("bi-eye");

                        icone.classList.add(
                            "bi-eye-slash"
                        );

                    } else {

                        input.type = "password";

                        icone.classList.remove(
                            "bi-eye-slash"
                        );

                        icone.classList.add("bi-eye");
                    }

                });

            });

        inputFoto.addEventListener("change", function() {

            const arquivo = this.files[0];

            if (!arquivo) {
                return;
            }

            if (!arquivo.type.startsWith("image/")) {

                alert(
                    "Selecione um arquivo de imagem válido."
                );

                this.value = "";

                return;
            }

            const urlImagem = URL.createObjectURL(
                arquivo
            );

            previewFoto.src = urlImagem;

            previewFoto.onload = function() {
                URL.revokeObjectURL(urlImagem);
            };

        });

        function validarSenhas() {

            if (
                senha.value !== "" ||
                confirmarSenha.value !== ""
            ) {

                if (
                    senha.value !==
                    confirmarSenha.value
                ) {

                    confirmarSenha.setCustomValidity(
                        "As senhas não coincidem."
                    );

                    erroSenha.classList.remove(
                        "d-none"
                    );

                    return false;
                }

            }

            confirmarSenha.setCustomValidity("");

            erroSenha.classList.add("d-none");

            return true;
        }

        senha.addEventListener(
            "input",
            validarSenhas
        );

        confirmarSenha.addEventListener(
            "input",
            validarSenhas
        );

        form.addEventListener(
            "submit",
            function(event) {

                validarSenhas();

                if (!form.checkValidity()) {

                    event.preventDefault();

                    form.reportValidity();
                }

            }
        );

    });
</script>