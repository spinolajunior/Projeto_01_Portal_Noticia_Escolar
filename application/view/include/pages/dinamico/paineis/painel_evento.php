<main class="container-fluid py-3 py-md-4">
    <style>
        .tabela-gerenciamento thead th {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
            font-weight: 600;
        }

        .tabela-gerenciamento tbody td {
            background-color: #fff;
        }

        .tabela-gerenciamento th:not(:last-child),
        .tabela-gerenciamento td:not(:last-child) {
            border-right: 1px solid #d9e2ef;
        }

        .tabela-gerenciamento thead th:not(:last-child) {
            border-right-color: rgba(255, 255, 255, .35);
        }

        .tabela-gerenciamento tbody tr {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .tabela-gerenciamento tbody tr:hover {
            box-shadow: inset 4px 0 0 #0d6efd;
            transform: translateY(-1px);
        }

        .tabela-gerenciamento tbody tr:hover td {
            background-color: #eef5ff;
        }

        .tabela-gerenciamento .acao-tabela {
            border-radius: .45rem;
            text-decoration: none;
            transition: all .18s ease;
        }

        .tabela-gerenciamento .acao-tabela:hover {
            transform: translateY(-1px);
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-1">Eventos</h1>
                    <p class="text-body-secondary mb-0">Acompanhe os eventos publicados pela escola.</p>
                </div>
                <a href="#" class="btn btn-primary">Novo evento</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 tabela-gerenciamento">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3 ps-md-4">ID</th>
                                    <th scope="col">Título</th>
                                    <th scope="col">Publicado por</th>
                                    <th scope="col" class="pe-3 pe-md-4 text-nowrap">Data da publicação</th>
                                    <th scope="col" class="pe-3 pe-md-4 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 ps-md-4">1</td>
                                    <td>Feira de Ciências 2026</td>
                                    <td>Marina Ferreira Costa</td>
                                    <td class="pe-3 pe-md-4 text-nowrap">18/03/2026</td>
                                    <td class="pe-3 pe-md-4 text-center text-nowrap"><a href="#" class="btn btn-sm btn-outline-primary acao-tabela me-2"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Editar</a><a href="#" class="btn btn-sm btn-outline-danger acao-tabela"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Excluir</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>