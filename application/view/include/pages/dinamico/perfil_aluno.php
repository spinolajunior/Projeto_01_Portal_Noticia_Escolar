<?php

use controller\Controller;

$usuario = $model['usuario'];
$foto = ($usuario->foto !== null) ? $usuario->foto : "/view/img/upload/perfil_usuario/user_def.jpg";

?>
<main class="container py-4 py-md-5">

    <style>
        /* CARD PRINCIPAL */

        .perfil-aluno {
            border-radius: 1rem;
            overflow: hidden;
        }

        /* CABEÇALHO */

        .perfil-aluno .cabecalho-perfil {
            background: linear-gradient(135deg,
                    #0d6efd,
                    #0a58ca);

            color: #fff;
        }

        .perfil-aluno .foto-perfil {
            width: 9rem;
            height: 9rem;
            object-fit: cover;

            background-color: #fff;
            border: 4px solid rgba(255, 255, 255, .9);
        }

        /* SEÇÕES */

        .perfil-aluno .secao-perfil {
            color: #0d6efd;
            font-weight: 700;
        }

        /* CAMPOS */

        .perfil-aluno .campo-perfil {
            background-color: #fff;
            border: 1px solid #e3eaf4;
            border-radius: .75rem;
            height: 100%;
            padding: 1rem;

            transition: border-color .2s ease;
        }

        .perfil-aluno .campo-perfil:hover {
            border-color: #b6d4fe;
        }

        .perfil-aluno .campo-label {
            display: block;
            color: #6c757d;
            font-size: .8rem;
            margin-bottom: .35rem;
        }

        .perfil-aluno .campo-valor {
            display: block;
            font-size: .95rem;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        /* RESPONSIVIDADE */

        @media (max-width: 575.98px) {

            .perfil-aluno .foto-perfil {
                width: 7rem;
                height: 7rem;
            }

            .perfil-aluno .cabecalho-perfil h1 {
                font-size: 1.35rem;
            }

            .perfil-aluno .botao-editar {
                width: 100%;
            }

        }
    </style>


    <div class="row justify-content-center">

        <div class="col-12 col-lg-10 col-xl-9">

            <section class="card perfil-aluno border-0 shadow-sm">


                <!-- =====================================
                     CABEÇALHO DO PERFIL
                ===================================== -->

                <header class="cabecalho-perfil px-3 px-md-5 py-4 py-md-5 text-center">


                    <!-- FOTO PADRÃO -->

                    <img
                        src="<?= $foto ?>"
                        alt="Foto de perfil padrão"
                        class="foto-perfil rounded-circle shadow-sm mb-3">


                    <!-- NOME -->

                    <h1 class="h3 fw-bold mb-2">
                        <?= $usuario->nome ?>
                    </h1>


                    <!-- IDENTIFICAÇÃO -->

                    <p class="mb-3 opacity-75">

                        <i class="bi bi-mortarboard-fill me-1"></i>

                        Aluno

                    </p>


                    <!-- STATUS -->


                    <?php if ($usuario->ativo == true): ?>

                        <span class="badge rounded-pill text-bg-success px-3 py-2">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Ativo

                        </span>

                    <?php else: ?>

                        <span class="badge rounded-pill text-bg-danger px-3 py-2">

                            <i class="bi bi-x-circle-fill me-1"></i>

                            Desativado

                        </span>

                    <?php endif; ?>



                </header>



                <!-- =====================================
                     CORPO DO PERFIL
                ===================================== -->

                <div class="card-body p-3 p-md-4 p-lg-5">


                    <!-- =====================================
                         DADOS PESSOAIS
                    ===================================== -->

                    <section class="mb-5">

                        <h2 class="h5 secao-perfil mb-3">

                            <i class="bi bi-person-vcard me-2"></i>

                            Dados pessoais

                        </h2>


                        <div class="row g-3">

                            <!-- NOME -->

                            <div class="col-12 col-md-6">

                                <div class="campo-perfil">

                                    <small class="campo-label">
                                        Nome completo
                                    </small>

                                    <span class="campo-valor">
                                        <?= $usuario->nome ?>
                                    </span>

                                </div>

                            </div>


                            <!-- MATRÍCULA -->

                            <div class="col-12 col-md-6">

                                <div class="campo-perfil">

                                    <small class="campo-label">
                                        Matrícula
                                    </small>

                                    <span class="campo-valor">
                                        <?= $usuario->matricula ?>
                                    </span>

                                </div>

                            </div>


                            <!-- DATA DE NASCIMENTO -->

                            <div class="col-12 col-md-6">

                                <div class="campo-perfil">

                                    <small class="campo-label">
                                        Data de nascimento
                                    </small>

                                    <span class="campo-valor">
                                        <?= Controller::formatarData($usuario->data_nascimento, "d/m/Y") ?>
                                    </span>

                                </div>

                            </div>


                            <!-- SÉRIE -->

                            <div class="col-12 col-md-6">

                                <div class="campo-perfil">

                                    <small class="campo-label">
                                        Série
                                    </small>

                                    <span class="campo-valor">
                                        <?= $usuario->serie ?>
                                    </span>

                                </div>

                            </div>

                        </div>

                    </section>



                </div>

            </section>

        </div>

    </div>

</main>