<?php
$codigo = (int)($model['codigo'] ?? 0);
$esc = static fn($valor): string => htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$apresentacoes = [
    0 => ['Em desenvolvimento', 'Estamos preparando esta página', 'bi-cone-striped', 'info', 'Novidades a caminho. Enquanto isso, você pode continuar navegando pelo portal.'],
    403 => ['Acesso protegido', 'Não foi possível concluir esta ação', 'bi-shield-lock-fill', 'warning', 'Confira a orientação abaixo para continuar. Se precisar de ajuda, procure a administração do portal.'],
    405 => ['Confira o acesso', 'Vamos voltar para uma página do portal', 'bi-signpost-split-fill', 'warning', 'Abra a opção desejada pelos menus ou formulários do sistema.'],
    409 => ['Cadastro encontrado', 'Esses dados já estão em uso', 'bi-person-exclamation', 'warning', 'Confira o usuário e a matrícula informados antes de tentar novamente.'],
    422 => ['Revise o formulário', 'Só falta conferir alguns dados', 'bi-exclamation-triangle-fill', 'warning', 'Veja a orientação abaixo e volte ao formulário para fazer o ajuste.'],
];
[$destaque, $tituloAviso, $icone, $cor, $orientacao] = $apresentacoes[$codigo]
    ?? ['Tente novamente', 'Tivemos um imprevisto', 'bi-exclamation-octagon-fill', 'danger', 'Volte à página anterior e tente novamente. Se o problema continuar, avise a administração do portal.'];
?>
<main class="container py-4 py-md-5 d-flex align-items-center justify-content-center flex-grow-1">
    <section class="portal-feedback-card text-center" aria-labelledby="tituloAviso">
        <div class="portal-feedback-band bg-<?= $cor ?>"></div>
        <div class="portal-feedback-body">
            <div class="portal-feedback-symbol bg-<?= $cor ?>-subtle text-<?= $cor ?>-emphasis" aria-hidden="true">
                <i class="bi <?= $icone ?>"></i>
            </div>
            <p class="portal-feedback-eyebrow text-<?= $cor ?>-emphasis mb-2"><?= $esc($destaque) ?></p>
            <h1 class="h3 portal-feedback-title mb-3" id="tituloAviso"><?= $esc($tituloAviso) ?></h1>
            <p class="text-body-secondary mb-4"><?= $esc($orientacao) ?></p>
            <div class="portal-feedback-message bg-<?= $cor ?>-subtle text-start mb-4" role="<?= $codigo > 0 ? 'alert' : 'status' ?>">
                <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                <?= $esc($model['mensagem'] ?? 'Não foi possível concluir a operação. Tente novamente em alguns instantes.') ?>
            </div>
            <div class="portal-feedback-actions">
                <?php if ($codigo > 0): ?>
                    <a href="/" class="btn btn-primary px-4" data-voltar>
                        <i class="bi bi-arrow-left me-2" aria-hidden="true"></i><?= in_array($codigo, [409, 422], true) ? 'Voltar e corrigir' : 'Voltar' ?>
                    </a>
                <?php endif; ?>
                <a href="/" class="btn btn-outline-secondary px-4"><i class="bi bi-house-door me-2" aria-hidden="true"></i>Página inicial</a>
            </div>
        </div>
    </section>
</main>
