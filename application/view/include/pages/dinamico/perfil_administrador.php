<?php
/**
 * Esta view espera um único array ou objeto em $administrador.
 * Campos do banco usados: administrador.*, tipo_adm.cargo/nivel_acesso,
 * contato.email/tel, endereco.cidade/cep/bairro/rua/complemento e
 * credenciais.usuario/ativo/criado_em/last_login.
 */
$perfil = $administrador ?? null;
$obter = static function ($fonte, string $campo, $padrao = '') {
    if (is_array($fonte)) {
        return $fonte[$campo] ?? $padrao;
    }

    return is_object($fonte) ? ($fonte->$campo ?? $padrao) : $padrao;
};
$texto = static function (string $campo, string $padrao = 'Não informado') use ($obter, $perfil) {
    $valor = $obter($perfil, $campo, '');
    return htmlspecialchars($valor === '' || $valor === null ? $padrao : (string) $valor, ENT_QUOTES, 'UTF-8');
};
$foto = trim((string) $obter($perfil, 'foto', ''));
$fotoUrl = $foto !== '' ? $foto : 'view/img/user.png';
$ativo = $obter($perfil, 'ativo', null);
?>

<main class="container py-4 py-md-5">
  <style>
    .perfil-administrador { border-radius: 1rem; overflow: hidden; }
    .perfil-administrador .cabecalho-perfil { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: #fff; }
    .perfil-administrador .foto-perfil { border: 4px solid rgba(255,255,255,.9); height: 9rem; object-fit: cover; width: 9rem; }
    .perfil-administrador .campo-perfil { background: #fff; border: 1px solid #e3eaf4; border-radius: .75rem; height: 100%; }
    .perfil-administrador .secao-perfil { color: #0d6efd; font-weight: 700; }
  </style>

  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">
      <section class="card perfil-administrador border-0 shadow-sm">
        <header class="cabecalho-perfil px-3 px-md-5 py-4 py-md-5 text-center">
          <img src="<?= htmlspecialchars($fotoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Foto de <?= $texto('nome', 'administrador') ?>" class="foto-perfil rounded-circle shadow-sm mb-3">
          <h1 class="h3 fw-bold mb-1"><?= $texto('nome') ?></h1>
          <p class="mb-0 opacity-75"><i class="bi bi-shield-check me-1" aria-hidden="true"></i><?= $texto('cargo', 'Administrador') ?></p>
        </header>

        <div class="card-body p-3 p-md-5">
          <section aria-labelledby="dados-pessoais" class="mb-5">
            <h2 id="dados-pessoais" class="h5 secao-perfil mb-3"><i class="bi bi-person-vcard me-2" aria-hidden="true"></i>Dados pessoais</h2>
            <div class="row g-3">
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Nome completo</small><span class="fw-semibold"><?= $texto('nome') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Matrícula</small><span class="fw-semibold"><?= $texto('matricula') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">CPF</small><span class="fw-semibold"><?= $texto('cpf') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Usuário de acesso</small><span class="fw-semibold"><?= $texto('usuario') ?></span></div></div>
            </div>
          </section>

          <section aria-labelledby="dados-administrativos" class="mb-5">
            <h2 id="dados-administrativos" class="h5 secao-perfil mb-3"><i class="bi bi-shield-lock me-2" aria-hidden="true"></i>Informações administrativas</h2>
            <div class="row g-3">
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Cargo</small><span class="fw-semibold"><?= $texto('cargo') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Nível de acesso</small><span class="fw-semibold"><?= $texto('nivel_acesso') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Situação da conta</small><?php if ($ativo === null || $ativo === ''): ?><span class="fw-semibold">Não informado</span><?php elseif ((int) $ativo === 1): ?><span class="badge text-bg-success">Ativa</span><?php else: ?><span class="badge text-bg-secondary">Inativa</span><?php endif; ?></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Último acesso</small><span class="fw-semibold"><?= $texto('last_login') ?></span></div></div>
            </div>
          </section>

          <section aria-labelledby="contato" class="mb-5">
            <h2 id="contato" class="h5 secao-perfil mb-3"><i class="bi bi-person-lines-fill me-2" aria-hidden="true"></i>Contato</h2>
            <div class="row g-3">
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">E-mail</small><span class="fw-semibold text-break"><?= $texto('email') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Telefone</small><span class="fw-semibold"><?= $texto('tel') ?></span></div></div>
            </div>
          </section>

          <section aria-labelledby="endereco">
            <h2 id="endereco" class="h5 secao-perfil mb-3"><i class="bi bi-geo-alt me-2" aria-hidden="true"></i>Endereço</h2>
            <div class="row g-3">
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Cidade</small><span class="fw-semibold"><?= $texto('cidade') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">CEP</small><span class="fw-semibold"><?= $texto('cep') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Bairro</small><span class="fw-semibold"><?= $texto('bairro') ?></span></div></div>
              <div class="col-12 col-md-6"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Rua</small><span class="fw-semibold"><?= $texto('rua') ?></span></div></div>
              <div class="col-12"><div class="campo-perfil p-3"><small class="text-body-secondary d-block">Complemento</small><span class="fw-semibold"><?= $texto('complemento') ?></span></div></div>
            </div>
          </section>

          <div class="d-flex justify-content-end mt-4">
            <a href="/perfil/administrador/update" class="btn btn-primary"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Editar perfil</a>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>
