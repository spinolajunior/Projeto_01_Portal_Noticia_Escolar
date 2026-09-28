<?php
$adm = $model['usuario'];
$foto = ($adm->foto !== null) ? $adm->foto : "/view/img/upload/perfil_usuario/user_def.jpg";

?>
<main class="container py-4 py-md-5">

  <style>
    /* CARD PRINCIPAL */

    .perfil-administrador {
      border-radius: 1rem;
      overflow: hidden;
    }

    /* CABEÇALHO */

    .perfil-administrador .cabecalho-perfil {
      background: linear-gradient(135deg,
          #0d6efd,
          #0a58ca);

      color: #fff;
    }

    .perfil-administrador .foto-perfil {
      width: 9rem;
      height: 9rem;
      object-fit: cover;

      background-color: #fff;
      border: 4px solid rgba(255, 255, 255, .9);
    }

    /* SEÇÕES */

    .perfil-administrador .secao-perfil {
      color: #0d6efd;
      font-weight: 700;
    }

    /* CAMPOS */

    .perfil-administrador .campo-perfil {
      background-color: #fff;
      border: 1px solid #e3eaf4;
      border-radius: .75rem;
      height: 100%;
      padding: 1rem;

      transition: border-color .2s ease;
    }

    .perfil-administrador .campo-perfil:hover {
      border-color: #b6d4fe;
    }

    .perfil-administrador .campo-label {
      display: block;
      color: #6c757d;
      font-size: .8rem;
      margin-bottom: .35rem;
    }

    .perfil-administrador .campo-valor {
      display: block;
      font-size: .95rem;
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    /* RESPONSIVIDADE */

    @media (max-width: 575.98px) {

      .perfil-administrador .foto-perfil {
        width: 7rem;
        height: 7rem;
      }

      .perfil-administrador .cabecalho-perfil h1 {
        font-size: 1.35rem;
      }

      .perfil-administrador .botao-editar {
        width: 100%;
      }

    }
  </style>


  <div class="row justify-content-center">

    <div class="col-12 col-lg-10 col-xl-9">

      <section class="card perfil-administrador border-0 shadow-sm">


        <!-- =====================================
                     CABEÇALHO DO PERFIL
                ===================================== -->

        <header class="cabecalho-perfil px-3 px-md-5 py-4 py-md-5 text-center">

          <!-- FOTO PADRÃO -->

          <img
            src=<?= $foto ?>
            alt="Foto de perfil padrão"
            class="foto-perfil rounded-circle shadow-sm mb-3">


          <!-- NOME -->

          <h1 class="h3 fw-bold mb-2">
            <?= $adm->nome ?>
          </h1>


          <!-- CARGO -->

          <p class="mb-3 opacity-75">

            <i class="bi bi-shield-check me-1"></i>

            <?= $adm->cargo ?>

          </p>


          <!-- STATUS -->

          <?php if ($adm->ativo == true): ?>

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
                    <?= $adm->nome ?>
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
                    <?= $adm->matricula ?>
                  </span>

                </div>

              </div>





              <!-- USUÁRIO -->

              <div class="col-12 col-md-6">

                <div class="campo-perfil">

                  <small class="campo-label">
                    Usuário de acesso
                  </small>

                  <span class="campo-valor">
                    <?= $adm->usuario ?>
                  </span>

                </div>

              </div>

            </div>

          </section>



          <!-- =====================================
                         INFORMAÇÕES ADMINISTRATIVAS
                    ===================================== -->

          <section class="mb-5">

            <h2 class="h5 secao-perfil mb-3">

              <i class="bi bi-shield-lock me-2"></i>

              Informações administrativas

            </h2>


            <div class="row g-3">

              <!-- CARGO -->

              <div class="col-12 col-md-6">

                <div class="campo-perfil">

                  <small class="campo-label">
                    Cargo
                  </small>

                  <span class="campo-valor">
                    <?= $adm->cargo ?>
                  </span>

                </div>

              </div>


              <!-- NÍVEL DE ACESSO -->

              <div class="col-12 col-md-6">

                <div class="campo-perfil">

                  <small class="campo-label">
                    Nível de acesso
                  </small>

                  <span class="campo-valor">
                    <?= $adm->acesso ?>
                  </span>

                </div>

              </div>


              <!-- CRIAÇÃO DA CONTA -->

              <div class="col-12 col-md-6">

                <div class="campo-perfil">

                  <small class="campo-label">
                    Conta criada em
                  </small>

                  <span class="campo-valor">
                    <?= $adm->criado_em ?>
                  </span>

                </div>

              </div>


              <!-- ÚLTIMO ACESSO -->

              <div class="col-12 col-md-6">

                <div class="campo-perfil">

                  <small class="campo-label">
                    Último acesso
                  </small>

                  <span class="campo-valor">
                    <?= $adm->last_login ?>
                  </span>

                </div>

              </div>

            </div>

          </section>










          <!-- =====================================
                         BOTÃO DE EDIÇÃO
                    ===================================== -->



        </div>

      </section>

    </div>

  </div>

</main>