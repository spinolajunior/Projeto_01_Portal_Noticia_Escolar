<?php
$adm = (object)($model['session'] ?? []);
$esc = static fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$fotoPadrao = '/view/img/upload/perfil_usuario/user_def.jpg';
$foto = $fotoPadrao;
$fotoBanco = trim((string)($adm->foto ?? ''));
$nomeArquivo = basename(str_replace('\\', '/', $fotoBanco));

if ($nomeArquivo !== '' && !in_array($nomeArquivo, ['.', '..'], true)) {
  $diretorioFoto = '/view/img/upload/perfil_usuario/';
  $raizes = array_unique(array_filter([
    $_SERVER['DOCUMENT_ROOT'] ?? '',
    defined('BASE_DIR') ? BASE_DIR : ''
  ]));

  foreach ($raizes as $raiz) {
    $caminhoFisico = rtrim($raiz, '/\\') . $diretorioFoto . $nomeArquivo;
    if (is_file($caminhoFisico) && is_readable($caminhoFisico)) {
      $foto = $diretorioFoto . rawurlencode($nomeArquivo);
      break;
    }
  }
}

$formatarData = static function ($valor): string {
  if (empty($valor)) return 'Não informado';
  try {
    return (new DateTimeImmutable((string)$valor))->format('d/m/Y \à\s H:i');
  } catch (Exception $e) {
    return 'Não informado';
  }
};

$nome = trim((string)($adm->nome ?? ''));
$nome = $nome !== '' ? $nome : 'Nome não informado';
$matricula = trim((string)($adm->matricula ?? ''));
$usuario = trim((string)($adm->usuario ?? ''));
$cargo = trim((string)($adm->cargo ?? ''));
$nivelAcesso = $adm->acesso ?? $adm->nivel ?? null;
$ativo = (bool)($adm->ativo ?? false);
?>
<main class="container py-4 py-md-5">
  <style>
    .perfil-administrador {
      border-radius: 1rem;
      overflow: hidden
    }

    .perfil-administrador .cabecalho-perfil {
      background: linear-gradient(135deg, #0d6efd, #0a58ca);
      color: #fff
    }

    .perfil-administrador .foto-perfil {
      width: 145px;
      height: 145px;
      object-fit: cover;
      background: #fff;
      border: 4px solid rgba(255, 255, 255, .9);
      border-radius: 50%
    }

    .perfil-administrador .nome-perfil {
      font-size: clamp(1.35rem, 2.5vw, 1.8rem);
      overflow-wrap: anywhere
    }

    .perfil-administrador .secao-perfil {
      color: #0d6efd;
      font-weight: 700
    }

    .perfil-administrador .campo-perfil {
      background: #fff;
      border: 1px solid #e3eaf4;
      border-radius: .75rem;
      height: 100%;
      padding: 1rem;
      transition: border-color .2s
    }

    .perfil-administrador .campo-perfil:hover {
      border-color: #b6d4fe
    }

    .perfil-administrador .campo-label {
      display: block;
      color: #6c757d;
      font-size: .8rem;
      margin-bottom: .35rem
    }

    .perfil-administrador .campo-valor {
      display: block;
      font-size: .95rem;
      font-weight: 600;
      color: #273444;
      overflow-wrap: anywhere
    }

    .perfil-administrador .campo-valor.vazio {
      font-weight: 400;
      color: #6c757d;
      font-style: italic
    }

    .perfil-administrador .separador-perfil {
      border-color: #e9edf2;
      opacity: 1
    }

    .perfil-administrador .botao-editar {
      min-width: 190px;
      border-radius: 8px
    }

    @media(max-width:575.98px) {
      .perfil-administrador .foto-perfil {
        width: 115px;
        height: 115px
      }

      .perfil-administrador .campo-perfil {
        padding: .9rem
      }

      .perfil-administrador .botao-editar {
        width: 100%
      }
    }
  </style>
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">
      <section class="card perfil-administrador border-0 shadow-sm">
        <header class="cabecalho-perfil px-3 px-md-5 py-4 py-md-5 text-center">
          <img src="<?= $esc($foto) ?>" alt="Foto de perfil de <?= $esc($nome) ?>" class="foto-perfil shadow-sm mb-3" onerror="this.onerror=null;this.src='/view/img/upload/perfil_usuario/user_def.jpg';">
          <h1 class="nome-perfil fw-bold mb-2"><?= $esc($nome) ?></h1>
          <p class="mb-3 opacity-75">
            <i class="bi bi-shield-check me-1"></i>
            <?= $esc($cargo !== '' ? $cargo : 'Administrador') ?>
          </p>
          <?php if ($ativo): ?>
            <span class="badge rounded-pill text-bg-success px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i>Ativo</span>
          <?php else: ?>
            <span class="badge rounded-pill text-bg-danger px-3 py-2"><i class="bi bi-x-circle-fill me-1"></i>Desativado</span>
          <?php endif; ?>
        </header>
        <div class="card-body p-3 p-md-4 p-lg-5">
          <section class="mb-4">
            <h2 class="h5 secao-perfil mb-3"><i class="bi bi-person-vcard me-2"></i>Dados pessoais</h2>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label">Nome completo</small>
                  <span class="campo-valor"><?= $esc($nome) ?></span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label">Matrícula</small>
                  <span class="campo-valor <?= $matricula === '' ? 'vazio' : '' ?>"><?= $esc($matricula !== '' ? $matricula : 'Não informada') ?></span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label">Usuário de acesso</small>
                  <span class="campo-valor <?= $usuario === '' ? 'vazio' : '' ?>"><?= $esc($usuario !== '' ? $usuario : 'Não informado') ?></span>
                </div>
              </div>
            </div>
          </section>
          <hr class="separador-perfil my-4">
          <section class="mb-4">
            <h2 class="h5 secao-perfil mb-3"><i class="bi bi-shield-lock me-2"></i>Informações administrativas</h2>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label">Cargo</small>
                  <span class="campo-valor <?= $cargo === '' ? 'vazio' : '' ?>"><?= $esc($cargo !== '' ? $cargo : 'Não informado') ?></span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label">Nível de acesso</small>
                  <span class="campo-valor"><?= $esc($nivelAcesso !== null && $nivelAcesso !== '' ? $nivelAcesso : 'Não informado') ?></span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label"><i class="bi bi-calendar-plus me-1"></i>Conta criada em</small>
                  <span class="campo-valor"><?= $esc($formatarData($adm->criado_em ?? null)) ?></span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="campo-perfil">
                  <small class="campo-label"><i class="bi bi-clock-history me-1"></i>Último acesso</small>
                  <span class="campo-valor"><?= $esc($formatarData($adm->last_login ?? null)) ?></span>
                </div>
              </div>
            </div>
          </section>
          <hr class="separador-perfil my-4">
          <div class="d-flex justify-content-end">
            <a href="/atualizar/myself" class="btn btn-primary fw-semibold px-4 py-2 botao-editar">
              <i class="bi bi-pencil-square me-2"></i>Editar meu perfil
            </a>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>