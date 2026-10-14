<?php
$noticia = $model['noticia'];
$adm = (object)($model['adm'] ?? []);
$esc = static fn($valor): string => htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$autor = trim((string)($adm->nome ?? ''));
$autor = $autor !== '' ? $autor : 'Autor não identificado';

$fotoAutorBanco = trim((string)($model['foto_autor'] ?? $adm->foto ?? ''));
$fotoPadrao = '/view/img/upload/perfil_usuario/user_def.jpg';
$fotoAutor = $fotoPadrao;

if ($fotoAutorBanco !== '') {
    $nomeArquivo = basename(str_replace('\\', '/', $fotoAutorBanco));
    if ($nomeArquivo !== '' && !in_array($nomeArquivo, ['.', '..'], true)) {
        $caminhoRelativo = '/view/img/upload/perfil_usuario/' . $nomeArquivo;
        $raizes = array_unique(array_filter([
            defined('BASE_DIR') ? BASE_DIR : null,
            $_SERVER['DOCUMENT_ROOT'] ?? null
        ]));

        foreach ($raizes as $raiz) {
            $caminhoFisico = rtrim($raiz, '/\\') . $caminhoRelativo;
            if (is_file($caminhoFisico) && is_readable($caminhoFisico)) {
                $fotoAutor = '/view/img/upload/perfil_usuario/' . rawurlencode($nomeArquivo);
                break;
            }
        }
    }
}

$imagem = trim((string)($noticia->imagem ?? ''));
if ($imagem !== '' && !preg_match('~^https?://~i', $imagem)) {
    $imagem = '/' . ltrim(str_replace('\\', '/', $imagem), '/');
}

$dataPublicacao = null;
if (!empty($noticia->data_pub)) {
    try {
        $dataPublicacao = new DateTimeImmutable((string)$noticia->data_pub);
    } catch (Exception $e) {
        $dataPublicacao = null;
    }
}

$conteudo = trim((string)($noticia->descricao ?? ''));
$paragrafos = preg_split('/\R{2,}/u', $conteudo) ?: [];
?>
<main class="container-fluid py-4 py-md-5">
    <style>
        .noticia-container {
            width: 85%;
            max-width: 1150px;
            margin: auto
        }

        .noticia-artigo {
            background: #fff;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(30, 41, 59, .045)
        }

        .noticia-corpo {
            padding: 38px 46px
        }

        .noticia-categoria {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #0d6efd;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .55px
        }

        .noticia-titulo {
            font-size: clamp(1.9rem, 3.4vw, 3rem);
            font-weight: 750;
            line-height: 1.2;
            letter-spacing: -.7px;
            color: #192638;
            overflow-wrap: anywhere;
            margin: 15px 0
        }

        .noticia-subtitulo {
            font-size: clamp(1.05rem, 1.6vw, 1.3rem);
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 25px
        }

        .noticia-autor {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 15px 17px;
            background: #f8fafc;
            border: 1px solid #edf0f4;
            border-radius: 11px
        }

        .noticia-autor-foto {
            width: 58px;
            height: 58px;
            min-width: 58px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            background: #e2e8f0
        }

        .noticia-autor-info {
            min-width: 0;
            flex: 1
        }

        .noticia-autor-legenda {
            font-size: .75rem;
            color: #64748b;
            display: block;
            margin-bottom: 2px
        }

        .noticia-autor-nome {
            font-size: .96rem;
            font-weight: 700;
            color: #25344a;
            overflow-wrap: anywhere
        }

        .noticia-autor-data {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 5px;
            font-size: .79rem;
            color: #64748b
        }

        .noticia-autor-data i {
            color: #0d6efd;
            margin-right: 4px
        }

        .noticia-separador {
            border-color: #e9edf2;
            opacity: 1;
            margin: 27px 0
        }

        .noticia-imagem {
            background: #f5f7fa;
            border: 1px solid #e9edf2;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            overflow: hidden
        }

        .noticia-imagem img {
            display: block;
            width: 100%;
            max-height: 520px;
            object-fit: contain;
            border-radius: 6px
        }

        .noticia-sem-imagem {
            padding: 36px 16px;
            color: #94a3b8;
            font-size: .9rem
        }

        .noticia-texto {
            font-size: 1.09rem;
            line-height: 1.9;
            color: #334155;
            overflow-wrap: anywhere
        }

        .noticia-texto p {
            margin-bottom: 1.35rem
        }

        .noticia-texto p:last-child {
            margin-bottom: 0
        }

        .noticia-acoes {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap
        }

        .noticia-acoes .btn {
            border-radius: 8px;
            padding: 10px 18px
        }

        @media(max-width:991px) {
            .noticia-container {
                width: 94%
            }

            .noticia-corpo {
                padding: 30px
            }
        }

        @media(max-width:576px) {
            .noticia-container {
                width: 100%;
                padding: 0 12px
            }

            .noticia-corpo {
                padding: 23px 18px
            }

            .noticia-titulo {
                font-size: 1.8rem;
                letter-spacing: -.3px
            }

            .noticia-autor {
                padding: 12px;
                gap: 10px
            }

            .noticia-autor-foto {
                width: 48px;
                height: 48px;
                min-width: 48px
            }

            .noticia-autor-nome {
                font-size: .88rem
            }

            .noticia-autor-data {
                font-size: .74rem;
                gap: 6px 12px
            }

            .noticia-imagem img {
                max-height: 300px
            }

            .noticia-texto {
                font-size: 1rem;
                line-height: 1.8
            }

            .noticia-acoes {
                flex-direction: column-reverse
            }

            .noticia-acoes .btn {
                width: 100%
            }
        }
    </style>
    <div class="noticia-container">
        <article class="noticia-artigo">
            <div class="noticia-corpo">
                <header>
                    <div class="noticia-categoria"><i class="bi bi-newspaper"></i>Notícia</div>
                    <h1 class="noticia-titulo"><?= $esc($noticia->titulo ?? '') ?></h1>
                    <?php if (!empty($noticia->subtitulo)): ?>
                        <p class="noticia-subtitulo"><?= $esc($noticia->subtitulo) ?></p>
                    <?php endif; ?>
                    <div class="noticia-autor">
                        <img src="<?= $esc($fotoAutor) ?>" alt="Foto de <?= $esc($autor) ?>" class="noticia-autor-foto" onerror="this.onerror=null;this.src='/view/img/upload/perfil_usuario/user_def.jpg';">
                        <div class="noticia-autor-info">
                            <span class="noticia-autor-legenda">Publicado por</span>
                            <div class="noticia-autor-nome"><?= $esc($autor) ?></div>
                            <?php if ($dataPublicacao !== null): ?>
                                <div class="noticia-autor-data">
                                    <span>
                                        <i class="bi bi-calendar3"></i>
                                        <time datetime="<?= $esc($dataPublicacao->format('Y-m-d\TH:i:s')) ?>"><?= $dataPublicacao->format('d/m/Y') ?></time>
                                    </span>
                                    <span><i class="bi bi-clock"></i><?= $dataPublicacao->format('H:i') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>
                <hr class="noticia-separador">
                <?php if ($imagem !== ''): ?>
                    <figure class="mb-4 mb-md-5">
                        <div class="noticia-imagem">
                            <img src="<?= $esc($imagem) ?>" alt="Imagem da notícia: <?= $esc($noticia->titulo ?? '') ?>" id="imagemNoticia">
                            <div id="semImagem" class="noticia-sem-imagem d-none">
                                <i class="bi bi-image fs-1 d-block mb-2"></i>Imagem indisponível
                            </div>
                        </div>
                    </figure>
                <?php endif; ?>
                <section class="noticia-texto" aria-label="Conteúdo da notícia">
                    <?php foreach ($paragrafos as $paragrafo): ?>
                        <?php if (trim($paragrafo) !== ''): ?>
                            <p><?= nl2br($esc(trim($paragrafo))) ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </section>
                <hr class="noticia-separador">
                <div class="noticia-acoes">
                    <a href="/" class="btn btn-primary fw-semibold"><i class="bi bi-arrow-left me-2"></i>Voltar para Notícias</a>
                    <button type="button" class="btn btn-outline-primary" id="btnCompartilhar"><i class="bi bi-share me-2"></i><span id="textoCompartilhar">Compartilhar notícia</span></button>
                </div>
            </div>
        </article>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imagem = document.getElementById('imagemNoticia');
        const semImagem = document.getElementById('semImagem');
        const compartilhar = document.getElementById('btnCompartilhar');
        const textoCompartilhar = document.getElementById('textoCompartilhar');

        if (imagem && semImagem) {
            const mostrarIndisponivel = () => {
                imagem.classList.add('d-none');
                semImagem.classList.remove('d-none');
            };

            imagem.addEventListener('error', mostrarIndisponivel);
            if (imagem.complete && imagem.naturalWidth === 0) mostrarIndisponivel();
        }

        compartilhar.addEventListener('click', async () => {
            try {
                if (navigator.share) {
                    await navigator.share({
                        title: document.title,
                        url: window.location.href
                    });
                } else if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(window.location.href);
                    textoCompartilhar.textContent = 'Link copiado!';
                    setTimeout(() => textoCompartilhar.textContent = 'Compartilhar notícia', 2500);
                } else {
                    window.prompt('Copie o link da notícia:', window.location.href);
                }
            } catch (erro) {
                if (erro.name !== 'AbortError') {
                    window.prompt('Copie o link da notícia:', window.location.href);
                }
            }
        });
    });
</script>