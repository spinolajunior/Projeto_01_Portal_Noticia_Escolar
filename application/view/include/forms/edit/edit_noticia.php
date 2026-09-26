
<?php
$noticia = $model['noticia'];
$imagemUrl = '/'.$model['url_imagem'];
?>

<main class="container py-4 py-lg-5 flex-grow-1">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-10 col-xl-9">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <div class="card-header bg-primary text-white p-3 p-md-4">

                    <h4 class="mb-1 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Notícia
                    </h4>

                    <p class="mb-0 small text-white-50">
                        Atualize as informações da notícia selecionada.
                    </p>

                </div>

                <div class="card-body p-3 p-md-4 p-lg-5">

                    <form method="POST"
                          action="/atualizar/noticia"
                          enctype="multipart/form-data">

                        <input type="hidden"
                               name="id"
                               value="<?= (int) $noticia->id ?>">

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Imagem da notícia
                            </label>

                            <div class="border rounded-3 bg-body-tertiary p-3 text-center">

                                <?php if ($imagemUrl !== ''): ?>

                                    <img
                                        id="previewImagem"
                                        src="<?= htmlspecialchars($imagemUrl, ENT_QUOTES, 'UTF-8').'.jpg' ?>"
                                        alt="Imagem atual da notícia"
                                        class="img-fluid rounded-3 shadow-sm"
                                        style="max-height: 320px; width: 100%; object-fit: contain;">

                                <?php else: ?>

                                    <div id="semImagem" class="py-5 text-secondary">

                                        <i class="bi bi-image fs-1 d-block mb-2"></i>

                                        Nenhuma imagem cadastrada.

                                    </div>

                                    <img
                                        id="previewImagem"
                                        class="img-fluid rounded-3 shadow-sm d-none"
                                        alt="Prévia da imagem"
                                        style="max-height: 320px; width: 100%; object-fit: contain;">

                                <?php endif; ?>

                            </div>

                        </div>

                        <!-- TROCAR IMAGEM -->
                        <div class="mb-4">

                            <label for="imagem" class="form-label fw-semibold">
                                Substituir imagem
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="imagem"
                                name="imagem"
                                accept="image/jpeg,image/png,image/webp">

                            <div class="form-text">
                                Se não selecionar uma nova imagem, a atual será mantida.
                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- TÍTULO -->
                        <div class="mb-3">

                            <label for="titulo" class="form-label fw-semibold">
                                Título da notícia
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="titulo"
                                name="titulo"
                                maxlength="50"
                                value="<?= htmlspecialchars($noticia->titulo, ENT_QUOTES, 'UTF-8') ?>"
                                required>

                        </div>

                        <!-- SUBTÍTULO -->
                        <div class="mb-3">

                            <label for="subtitulo" class="form-label fw-semibold">
                                Subtítulo
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="subtitulo"
                                name="subtitulo"
                                maxlength="100"
                                value="<?= htmlspecialchars($noticia->subtitulo ?? '', ENT_QUOTES, 'UTF-8') ?>">

                        </div>

                        <!-- DESCRIÇÃO -->
                        <div class="mb-3">

                            <label for="descricao" class="form-label fw-semibold">
                                Conteúdo da notícia
                            </label>

                            <textarea
                                class="form-control"
                                id="descricao"
                                name="descricao"
                                rows="8"
                                required><?= htmlspecialchars($noticia->descricao, ENT_QUOTES, 'UTF-8') ?></textarea>

                        </div>

                        <!-- DATA E STATUS -->
                        <div class="row g-3 mb-4">


                            <div class="col-12 col-md-6">

                                <label for="status" class="form-label fw-semibold">
                                    Situação
                                </label>

                                <select
                                    class="form-select"
                                    id="status"
                                    name="status">

                                    <option value="1"
                                        <?= $noticia->status ? 'selected' : '' ?>>
                                        Ativo
                                    </option>

                                    <option value="0"
                                        <?= !$noticia->status ? 'selected' : '' ?>>
                                        Desativada
                                    </option>

                                </select>

                            </div>

                        </div>

                        <!-- BOTÕES -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">

                            <a href="/painel/noticia"
                               class="btn btn-outline-secondary px-4">

                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4 fw-semibold">

                                <i class="bi bi-check-circle me-1"></i>
                                Salvar Alterações

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- PRÉ-VISUALIZAÇÃO DA NOVA IMAGEM -->
<script>
document.getElementById('imagem').addEventListener('change', function () {

    const arquivo = this.files[0];

    if (!arquivo) return;

    const preview = document.getElementById('previewImagem');
    const semImagem = document.getElementById('semImagem');

    preview.src = URL.createObjectURL(arquivo);

    preview.classList.remove('d-none');

    if (semImagem) {
        semImagem.classList.add('d-none');
    }

});
</script>
