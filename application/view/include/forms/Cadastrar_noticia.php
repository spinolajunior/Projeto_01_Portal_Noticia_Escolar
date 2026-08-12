<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h1 class="h4 card-title mb-0">Nova Notícia</h1>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form action="/noticia/cadastro" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-bold">Título da Notícia</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" maxlength="150" required placeholder="Digite um título">
                        </div>

                        <div class="mb-3">
                            <label for="subtitulo" class="form-label fw-bold">Subtítulo / Resumo</label>
                            <input type="text" class="form-control" id="subtitulo" name="subtitulo" maxlength="255" placeholder="Resumo ">
                        </div>

                        <div class="mb-3">
                            <label for="texto" class="form-label fw-bold">Conteúdo Principal</label>
                            <textarea class="form-control" id="texto" name="descricao" rows="8" required placeholder="Texto completo da notícia"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="img" class="form-label fw-bold">Imagem de Capa</label>
                            <input type="file" class="form-control" id="img" name="img" accept="image/png, image/jpeg, image/webp" required>
                            <div class="form-text">Formatos permitidos: JPG, PNG, WEBP.</div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-column flex-sm-row justify-content-sm-start gap-2 mb-2">
                            <button type="submit" class="btn btn-sm btn-primary px-3">Salvar Notícia</button>
                            <a href="index.php?controller=Noticia&action=listar" class="btn btn-sm btn-outline-secondary px-3">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</main>